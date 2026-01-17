<?php

namespace App\Http\Controllers;

use App\Models\ApplicationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReportSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the report settings page.
     */
    public function index()
    {
        // Permission check
        if (!Gate::allows('viewAny', ApplicationSetting::class)) {
            abort(403);
        }

        $settings = [
            'default_format' => ApplicationSetting::getValue('report.default_format', 'pdf'),
            'auto_generate_monthly' => ApplicationSetting::getValue('report.auto_generate_monthly', true),
            'monthly_generation_day' => ApplicationSetting::getValue('report.monthly_generation_day', 1),
            'max_export_rows' => ApplicationSetting::getValue('report.max_export_rows', 10000),
            'include_charts' => ApplicationSetting::getValue('report.include_charts', true),
            'cache_duration_minutes' => ApplicationSetting::getValue('report.cache_duration_minutes', 60),
        ];

        return view('report-settings.index', compact('settings'));
    }

    /**
     * Update report settings.
     */
    public function update(Request $request)
    {
        // Only Super Admin can edit settings
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'default_format' => 'required|in:pdf,excel',
            'auto_generate_monthly' => 'boolean',
            'monthly_generation_day' => 'required|integer|min:1|max:28',
            'max_export_rows' => 'required|integer|min:100|max:100000',
            'include_charts' => 'boolean',
            'cache_duration_minutes' => 'required|integer|min:0|max:10080', // Max 1 week
        ]);

        try {
            DB::beginTransaction();

            $this->updateOrCreateSetting('report.default_format', $validated['default_format'], 'Format default untuk laporan', 'string');
            $this->updateOrCreateSetting('report.auto_generate_monthly', $request->has('auto_generate_monthly'), 'Otomatis generate laporan bulanan', 'boolean');
            $this->updateOrCreateSetting('report.monthly_generation_day', $validated['monthly_generation_day'], 'Tanggal generate laporan bulanan (1-28)', 'integer');
            $this->updateOrCreateSetting('report.max_export_rows', $validated['max_export_rows'], 'Maksimal baris data untuk export', 'integer');
            $this->updateOrCreateSetting('report.include_charts', $request->has('include_charts'), 'Sertakan grafik dalam laporan PDF', 'boolean');
            $this->updateOrCreateSetting('report.cache_duration_minutes', $validated['cache_duration_minutes'], 'Durasi cache data laporan (menit)', 'integer');

            DB::commit();

            return redirect()->route('report-settings.index')
                ->with('success', 'Pengaturan laporan berhasil diperbarui.');

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
                'category' => 'report',
                'description' => $description,
                'is_active' => true,
            ]
        );

        // Clear cache for this key
        \Illuminate\Support\Facades\Cache::forget("app_setting_{$key}");
    }
}
