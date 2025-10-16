<?php

namespace Database\Seeders;

use App\Models\ApplicationSetting;
use Illuminate\Database\Seeder;

class ApplicationSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General Application Settings
            [
                'key' => 'app.name',
                'value' => 'WASPRO - Waste Management System',
                'type' => 'string',
                'category' => 'general',
                'description' => 'Nama aplikasi yang ditampilkan di header dan title',
                'is_active' => true,
            ],
            [
                'key' => 'app.version',
                'value' => '1.0.0',
                'type' => 'string',
                'category' => 'general',
                'description' => 'Versi aplikasi saat ini',
                'is_active' => true,
            ],
            [
                'key' => 'app.maintenance_mode',
                'value' => false,
                'type' => 'boolean',
                'category' => 'general',
                'description' => 'Mode maintenance untuk aplikasi',
                'is_active' => true,
            ],
            [
                'key' => 'app.timezone',
                'value' => 'Asia/Jakarta',
                'type' => 'string',
                'category' => 'general',
                'description' => 'Timezone default aplikasi',
                'is_active' => true,
            ],

            // User Management Settings
            [
                'key' => 'user.max_login_attempts',
                'value' => 5,
                'type' => 'integer',
                'category' => 'security',
                'description' => 'Maksimal percobaan login sebelum akun dikunci',
                'is_active' => true,
            ],
            [
                'key' => 'user.lockout_duration',
                'value' => 15,
                'type' => 'integer',
                'category' => 'security',
                'description' => 'Durasi penguncian akun dalam menit',
                'is_active' => true,
            ],
            [
                'key' => 'user.password_min_length',
                'value' => 8,
                'type' => 'integer',
                'category' => 'security',
                'description' => 'Panjang minimum password',
                'is_active' => true,
            ],
            [
                'key' => 'user.require_email_verification',
                'value' => true,
                'type' => 'boolean',
                'category' => 'security',
                'description' => 'Wajibkan verifikasi email untuk pengguna baru',
                'is_active' => true,
            ],

            // File Upload Settings
            [
                'key' => 'upload.max_file_size',
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

            // Notification Settings
            [
                'key' => 'notification.email_enabled',
                'value' => true,
                'type' => 'boolean',
                'category' => 'notification',
                'description' => 'Aktifkan notifikasi email',
                'is_active' => true,
            ],
            [
                'key' => 'notification.admin_email',
                'value' => 'admin@waspro.com',
                'type' => 'string',
                'category' => 'notification',
                'description' => 'Email administrator untuk notifikasi sistem',
                'is_active' => true,
            ],

            // Data Management Settings
            [
                'key' => 'data.pagination_limit',
                'value' => 15,
                'type' => 'integer',
                'category' => 'data',
                'description' => 'Jumlah data per halaman pada tabel',
                'is_active' => true,
            ],
            [
                'key' => 'data.export_limit',
                'value' => 1000,
                'type' => 'integer',
                'category' => 'data',
                'description' => 'Maksimal data yang dapat diekspor sekaligus',
                'is_active' => true,
            ],
            [
                'key' => 'data.backup_retention_days',
                'value' => 30,
                'type' => 'integer',
                'category' => 'data',
                'description' => 'Lama penyimpanan backup data dalam hari',
                'is_active' => true,
            ],

            // Waste Management Specific Settings
            [
                'key' => 'waste.default_unit',
                'value' => 'kg',
                'type' => 'string',
                'category' => 'waste',
                'description' => 'Unit default untuk pengukuran limbah',
                'is_active' => true,
            ],
            [
                'key' => 'waste.alert_threshold',
                'value' => 80,
                'type' => 'integer',
                'category' => 'waste',
                'description' => 'Persentase threshold untuk alert kapasitas limbah',
                'is_active' => true,
            ],
            [
                'key' => 'waste.categories',
                'value' => json_encode(['B3', 'Non-B3', 'Organik', 'Anorganik']),
                'type' => 'json',
                'category' => 'waste',
                'description' => 'Kategori limbah yang tersedia',
                'is_active' => true,
            ],

            // Report Settings
            [
                'key' => 'report.auto_generate',
                'value' => true,
                'type' => 'boolean',
                'category' => 'report',
                'description' => 'Otomatis generate laporan bulanan',
                'is_active' => true,
            ],
            [
                'key' => 'report.default_format',
                'value' => 'pdf',
                'type' => 'string',
                'category' => 'report',
                'description' => 'Format default untuk laporan',
                'is_active' => true,
            ],

            // Expiry Settings (unify to application_settings)
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
        ];

        foreach ($settings as $setting) {
            ApplicationSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
