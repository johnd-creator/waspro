<?php

namespace App\Http\Controllers;

use App\Models\ApplicationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class WorkflowSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the workflow settings page.
     */
    public function index()
    {
        // Permission check: only Super Admin, Administrator, Supervisor, Management
        if (!Gate::allows('viewAny', ApplicationSetting::class)) {
            abort(403);
        }

        $settings = [
            'approval_required' => ApplicationSetting::getValue('workflow.approval_required', true),
            'auto_approve_operator' => ApplicationSetting::getValue('workflow.auto_approve_operator', false),
            'approval_timeout_hours' => ApplicationSetting::getValue('workflow.approval_timeout_hours', 72),
            'require_rejection_reason' => ApplicationSetting::getValue('workflow.require_rejection_reason', true),
            'edit_approved_logs' => ApplicationSetting::getValue('workflow.edit_approved_logs', false),
            'delete_approved_logs' => ApplicationSetting::getValue('workflow.delete_approved_logs', false),
        ];

        return view('workflow-settings.index', compact('settings'));
    }

    /**
     * Update workflow settings.
     */
    public function update(Request $request)
    {
        // Only Super Admin can edit settings
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'approval_required' => 'boolean',
            'auto_approve_operator' => 'boolean',
            'approval_timeout_hours' => 'required|integer|min:1|max:720', // Max 1 month
            'require_rejection_reason' => 'boolean',
            'edit_approved_logs' => 'boolean',
            'delete_approved_logs' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $this->updateOrCreateSetting('workflow.approval_required', $request->has('approval_required'), 'Wajibkan persetujuan supervisor untuk log limbah baru', 'boolean');
            $this->updateOrCreateSetting('workflow.auto_approve_operator', $request->has('auto_approve_operator'), 'Otomatis setujui log dari operator terpercaya', 'boolean');
            $this->updateOrCreateSetting('workflow.approval_timeout_hours', $request->approval_timeout_hours, 'Batasan waktu (jam) sebelum log pending otomatis ditolak', 'integer');
            $this->updateOrCreateSetting('workflow.require_rejection_reason', $request->has('require_rejection_reason'), 'Wajibkan alasan penolakan saat menolak log', 'boolean');
            $this->updateOrCreateSetting('workflow.edit_approved_logs', $request->has('edit_approved_logs'), 'Izinkan pengeditan log yang sudah disetujui', 'boolean');
            $this->updateOrCreateSetting('workflow.delete_approved_logs', $request->has('delete_approved_logs'), 'Izinkan penghapusan log yang sudah disetujui', 'boolean');

            DB::commit();

            return redirect()->route('workflow-settings.index')
                ->with('success', 'Pengaturan workflow berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Helper to update or create a setting.
     */
    private function updateOrCreateSetting($key, $value, $description, $type)
    {
        ApplicationSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'category' => 'workflow',
                'description' => $description,
                'is_active' => true,
            ]
        );

        // Clear cache for this key
        \Illuminate\Support\Facades\Cache::forget("app_setting_{$key}");
    }
}
