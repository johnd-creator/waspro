<?php

namespace App\Http\Controllers;

use App\Models\ApplicationSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class UploadSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the upload settings page.
     */
    public function index()
    {
        // Permission check - Only Super Admin can access
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $settings = [
            'max_file_size_kb' => ApplicationSetting::getValue('upload.max_file_size_kb', 10240),
            'allowed_extensions' => $this->getExtensionsArray(),
            'require_document_for_transport' => ApplicationSetting::getValue('upload.require_document_for_transport', true),
        ];

        return view('upload-settings.index', compact('settings'));
    }

    /**
     * Update upload settings.
     */
    public function update(Request $request)
    {
        // Only Super Admin can edit settings
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'max_file_size_kb' => 'required|integer|min:100|max:51200', // Max 50MB
            'allowed_extensions' => 'required|string', // Comma separated string from input
            'require_document_for_transport' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            // Process extensions string to array
            $extensions = array_map('trim', explode(',', $validated['allowed_extensions']));
            $extensions = array_filter($extensions); // Remove empty values

            $this->updateOrCreateSetting('upload.max_file_size_kb', $validated['max_file_size_kb'], 'Maksimal ukuran file upload dalam KB', 'integer');
            $this->updateOrCreateSetting('upload.allowed_extensions', json_encode(array_values($extensions)), 'Ekstensi file yang diizinkan untuk upload', 'json');
            $this->updateOrCreateSetting('upload.require_document_for_transport', $request->has('require_document_for_transport'), 'Wajibkan bukti dokumen saat mengubah status menjadi Diangkut', 'boolean');

            DB::commit();

            return redirect()->route('upload-settings.index')
                ->with('success', 'Pengaturan upload berhasil diperbarui.');

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
                'category' => 'upload',
                'description' => $description,
                'is_active' => true,
            ]
        );

        // Clear cache for this key
        \Illuminate\Support\Facades\Cache::forget("app_setting_{$key}");
    }

    /**
     * Helper to get extensions as array (handles both string and array types).
     */
    private function getExtensionsArray(): array
    {
        $extensions = ApplicationSetting::getValue('upload.allowed_extensions', '["pdf","doc","docx","xls","xlsx","jpg","jpeg","png"]');

        // If already an array, return it
        if (is_array($extensions)) {
            return $extensions;
        }

        // If string, try to json_decode
        if (is_string($extensions)) {
            $decoded = json_decode($extensions, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        // Fallback to default
        return ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png'];
    }
}
