<?php

namespace Database\Seeders;

use App\Models\ApplicationSetting;
use Illuminate\Database\Seeder;

class ApplicationSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * NOTE: This seeder contains ONLY actively used settings.
     * Do not add new settings without implementing them in code first.
     */
    public function run(): void
    {
        $settings = [
            // =====================================================
            // SECURITY SETTINGS (Used in LoginController)
            // =====================================================
            [
                'key' => 'security.max_login_attempts',
                'value' => 5,
                'type' => 'integer',
                'category' => 'security',
                'description' => 'Maksimal percobaan login sebelum akun dikunci',
                'is_active' => true,
            ],
            [
                'key' => 'security.lockout_duration_minutes',
                'value' => 15,
                'type' => 'integer',
                'category' => 'security',
                'description' => 'Durasi penguncian akun dalam menit',
                'is_active' => true,
            ],
            [
                'key' => 'security.password_min_length',
                'value' => 8,
                'type' => 'integer',
                'category' => 'security',
                'description' => 'Panjang minimum password',
                'is_active' => true,
            ],

            // =====================================================
            // SYSTEM SETTINGS (Used in CheckMaintenanceMode)
            // =====================================================
            [
                'key' => 'system.maintenance_mode',
                'value' => false,
                'type' => 'boolean',
                'category' => 'system',
                'description' => 'Mode maintenance untuk aplikasi',
                'is_active' => true,
            ],

            // =====================================================
            // UPLOAD SETTINGS (Used in LogPenyimpananLimbahController)
            // =====================================================
            [
                'key' => 'upload.max_file_size_kb',
                'value' => 10240,
                'type' => 'integer',
                'category' => 'upload',
                'description' => 'Maksimal ukuran file upload dalam KB',
                'is_active' => true,
            ],
            [
                'key' => 'upload.allowed_extensions',
                'value' => json_encode(['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png']),
                'type' => 'json',
                'category' => 'upload',
                'description' => 'Ekstensi file yang diizinkan untuk upload',
                'is_active' => true,
            ],
            [
                'key' => 'upload.require_document_for_transport',
                'value' => true,
                'type' => 'boolean',
                'category' => 'upload',
                'description' => 'Wajibkan bukti dokumen saat mengubah status menjadi Diangkut',
                'is_active' => true,
            ],

            // =====================================================
            // EXPIRY SETTINGS (Used in LogPenyimpananLimbah, NotificationController, DashboardController)
            // =====================================================
            [
                'key' => 'critical_days',
                'value' => 7,
                'type' => 'integer',
                'category' => 'expiry',
                'description' => 'Jumlah hari untuk status kritis sebelum kadaluarsa',
                'is_active' => true,
            ],
            [
                'key' => 'warning_days',
                'value' => 30,
                'type' => 'integer',
                'category' => 'expiry',
                'description' => 'Jumlah hari untuk status peringatan sebelum kadaluarsa',
                'is_active' => true,
            ],
            [
                'key' => 'expiry.urgent_days',
                'value' => 3,
                'type' => 'integer',
                'category' => 'expiry',
                'description' => 'Jumlah hari untuk status urgent sebelum kadaluarsa',
                'is_active' => true,
            ],

            // =====================================================
            // WORKFLOW SETTINGS (Used in LogPenyimpananLimbahController, CleanupPendingLogs)
            // =====================================================
            [
                'key' => 'workflow.approval_required',
                'value' => true,
                'type' => 'boolean',
                'category' => 'workflow',
                'description' => 'Wajibkan persetujuan supervisor untuk log limbah baru',
                'is_active' => true,
            ],
            [
                'key' => 'workflow.auto_approve_operator',
                'value' => false,
                'type' => 'boolean',
                'category' => 'workflow',
                'description' => 'Otomatis setujui log dari operator terpercaya',
                'is_active' => true,
            ],
            [
                'key' => 'workflow.approval_timeout_hours',
                'value' => 72,
                'type' => 'integer',
                'category' => 'workflow',
                'description' => 'Batasan waktu (jam) sebelum log pending otomatis ditolak',
                'is_active' => true,
            ],
            [
                'key' => 'workflow.require_rejection_reason',
                'value' => true,
                'type' => 'boolean',
                'category' => 'workflow',
                'description' => 'Wajibkan alasan penolakan saat menolak log',
                'is_active' => true,
            ],
            [
                'key' => 'workflow.edit_approved_logs',
                'value' => false,
                'type' => 'boolean',
                'category' => 'workflow',
                'description' => 'Izinkan pengeditan log yang sudah disetujui',
                'is_active' => true,
            ],
            [
                'key' => 'workflow.delete_approved_logs',
                'value' => false,
                'type' => 'boolean',
                'category' => 'workflow',
                'description' => 'Izinkan penghapusan log yang sudah disetujui',
                'is_active' => true,
            ],

            // =====================================================
            // REPORT SETTINGS (Used in MonthlyReportGenerator, LogPenyimpananLimbahController)
            // =====================================================
            [
                'key' => 'report.default_format',
                'value' => 'pdf',
                'type' => 'string',
                'category' => 'report',
                'description' => 'Format default untuk laporan',
                'is_active' => true,
            ],
            [
                'key' => 'report.auto_generate_monthly',
                'value' => true,
                'type' => 'boolean',
                'category' => 'report',
                'description' => 'Otomatis generate laporan bulanan',
                'is_active' => true,
            ],
            [
                'key' => 'report.monthly_generation_day',
                'value' => 1,
                'type' => 'integer',
                'category' => 'report',
                'description' => 'Tanggal generate laporan bulanan (1-28)',
                'is_active' => true,
            ],
            [
                'key' => 'report.max_export_rows',
                'value' => 10000,
                'type' => 'integer',
                'category' => 'report',
                'description' => 'Maksimal baris data untuk export',
                'is_active' => true,
            ],
            [
                'key' => 'report.include_charts',
                'value' => true,
                'type' => 'boolean',
                'category' => 'report',
                'description' => 'Sertakan grafik dalam laporan PDF',
                'is_active' => true,
            ],
            [
                'key' => 'report.cache_duration_minutes',
                'value' => 60,
                'type' => 'integer',
                'category' => 'report',
                'description' => 'Durasi cache data laporan (menit)',
                'is_active' => true,
            ],
        ];

        foreach ($settings as $setting) {
            ApplicationSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        // Clean up old/unused settings from previous versions
        $activeKeys = array_column($settings, 'key');
        ApplicationSetting::whereNotIn('key', $activeKeys)->delete();
    }
}
