<?php

namespace Database\Seeders;

use App\Models\ApplicationSetting;
use App\Models\AuditLog;
use App\Models\JenisLimbah;
use App\Models\KarakteristikLimbah;
use App\Models\KategoriKegiatanSumber;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use App\Models\PeranPengguna;
use App\Models\PerusahaanPenghasil;
use App\Models\UnitPembangkit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DevelopmentDataSeeder extends Seeder
{
    public function run(): void
    {
        echo "\n=== WASPRO Development Data Generator ===\n\n";

        DB::beginTransaction();

        try {
            $this->generateRoles();

            $this->generateSupportingData();

            $this->generateJenisLimbah();

            $this->generateUsers();

            $this->generateLogPenyimpananLimbah();

            $this->generateAuditLogs();

            $this->generateApplicationSettings();

            DB::commit();

            echo "\n✅ All data generated successfully!\n";
            echo "===========================================\n\n";

        } catch (\Exception $e) {
            DB::rollBack();
            echo "\n❌ Error: ".$e->getMessage()."\n";
            echo 'Stack trace: '.$e->getTraceAsString()."\n";
            throw $e;
        }
    }

    private function generateRoles(): void
    {
        echo "📋 Generating Roles...\n";

        $roles = [
            ['nama_peran' => 'Super Admin', 'deskripsi' => 'Akses penuh ke seluruh sistem tanpa batasan unit', 'is_active' => true],
            ['nama_peran' => 'Administrator', 'deskripsi' => 'Dapat mengelola semua data dalam unit', 'is_active' => true],
            ['nama_peran' => 'Supervisor', 'deskripsi' => 'Dapat menyetujui dan memverifikasi data limbah', 'is_active' => true],
            ['nama_peran' => 'Operator', 'deskripsi' => 'Dapat mengelola data limbah dan laporan', 'is_active' => true],
            ['nama_peran' => 'Viewer', 'deskripsi' => 'Hanya dapat melihat data tanpa mengubah', 'is_active' => true],
        ];

        foreach ($roles as $role) {
            PeranPengguna::firstOrCreate(
                ['nama_peran' => $role['nama_peran']],
                $role
            );
        }

        echo "   ✓ Created/verified 5 roles\n";
    }

    private function generateSupportingData(): void
    {
        echo "\n🏢 Generating Supporting Data...\n";

        $unitCount = UnitPembangkit::count();
        if ($unitCount < 5) {
            $units = [
                ['nama_unit' => 'Unit Pembangkit Jakarta Pusat', 'lokasi_unit' => 'Jakarta Pusat, DKI Jakarta', 'kapasitas_mw' => 500],
                ['nama_unit' => 'Unit Pembangkit Surabaya', 'lokasi_unit' => 'Surabaya, Jawa Timur', 'kapasitas_mw' => 400],
                ['nama_unit' => 'Unit Pembangkit Medan', 'lokasi_unit' => 'Medan, Sumatera Utara', 'kapasitas_mw' => 350],
                ['nama_unit' => 'Unit Pembangkit Bandung', 'lokasi_unit' => 'Bandung, Jawa Barat', 'kapasitas_mw' => 300],
                ['nama_unit' => 'Unit Pembangkit Semarang', 'lokasi_unit' => 'Semarang, Jawa Tengah', 'kapasitas_mw' => 250],
            ];

            foreach ($units as $unit) {
                UnitPembangkit::firstOrCreate(
                    ['nama_unit' => $unit['nama_unit']],
                    $unit
                );
            }
            echo '   ✓ Created '.(5 - $unitCount)." units (total: 5)\n";
        } else {
            echo "   ✓ Units already exist: {$unitCount}\n";
        }

        $karakteristikCount = KarakteristikLimbah::count();
        if ($karakteristikCount < 5) {
            KarakteristikLimbah::factory()->count(5 - $karakteristikCount)->create();
            echo '   ✓ Created '.(5 - $karakteristikCount)." karakteristik limbah (total: 5)\n";
        } else {
            echo "   ✓ Karakteristik limbah already exist: {$karakteristikCount}\n";
        }

        $kategoriCount = KategoriKegiatanSumber::count();
        if ($kategoriCount < 5) {
            KategoriKegiatanSumber::factory()->count(5 - $kategoriCount)->create();
            echo '   ✓ Created '.(5 - $kategoriCount)." kategori kegiatan (total: 5)\n";
        } else {
            echo "   ✓ Kategori kegiatan already exist: {$kategoriCount}\n";
        }

        $perusahaanCount = PerusahaanPenghasil::count();
        if ($perusahaanCount < 10) {
            PerusahaanPenghasil::factory()->count(10 - $perusahaanCount)->create();
            echo '   ✓ Created '.(10 - $perusahaanCount)." perusahaan (total: 10)\n";
        } else {
            echo "   ✓ Perusahaan already exist: {$perusahaanCount}\n";
        }
    }

    private function generateJenisLimbah(): void
    {
        echo "\n🗑️  Generating Jenis Limbah...\n";

        $existingCount = JenisLimbah::count();
        $targetCount = 20;

        if ($existingCount >= $targetCount) {
            echo "   ✓ Jenis limbah already exist: {$existingCount}\n";

            return;
        }

        $toCreate = $targetCount - $existingCount;

        $highCost = min(5, (int) ($toCreate * 0.25));
        $mediumCost = min(8, (int) ($toCreate * 0.40));
        $lowCost = $toCreate - $highCost - $mediumCost;

        for ($i = 0; $i < $highCost; $i++) {
            JenisLimbah::factory()->create([
                'biaya_pengangkutan_per_kg' => fake()->randomFloat(2, 100000, 150000),
                'status_aktif' => true,
            ]);
        }

        for ($i = 0; $i < $mediumCost; $i++) {
            JenisLimbah::factory()->create([
                'biaya_pengangkutan_per_kg' => fake()->randomFloat(2, 80000, 100000),
                'status_aktif' => true,
            ]);
        }

        for ($i = 0; $i < $lowCost; $i++) {
            JenisLimbah::factory()->create([
                'biaya_pengangkutan_per_kg' => fake()->randomFloat(2, 50000, 80000),
                'status_aktif' => $i < ($lowCost - 5),
            ]);
        }

        echo "   ✓ Created {$toCreate} jenis limbah:\n";
        echo "      - {$highCost} high cost (>100k)\n";
        echo "      - {$mediumCost} medium cost (80k-100k)\n";
        echo "      - {$lowCost} low cost (<80k)\n";
        echo '      - '.($toCreate - 5)." active, 5 inactive\n";
    }

    private function generateUsers(): void
    {
        echo "\n👥 Generating Users with Roles...\n";

        $existingCount = PenggunaSistem::count();
        $targetCount = 50;

        if ($existingCount >= $targetCount) {
            echo "   ✓ Users already exist: {$existingCount}\n";

            return;
        }

        $roles = [
            'Super Admin' => PeranPengguna::where('nama_peran', 'Super Admin')->first(),
            'Administrator' => PeranPengguna::where('nama_peran', 'Administrator')->first(),
            'Supervisor' => PeranPengguna::where('nama_peran', 'Supervisor')->first(),
            'Operator' => PeranPengguna::where('nama_peran', 'Operator')->first(),
            'Viewer' => PeranPengguna::where('nama_peran', 'Viewer')->first(),
        ];

        $units = UnitPembangkit::all();
        $created = 0;

        if (! PenggunaSistem::whereHas('peranPengguna', fn ($q) => $q->where('nama_peran', 'Super Admin'))->exists()) {
            $superAdmin = PenggunaSistem::create([
                'nama_lengkap' => 'Super Administrator',
                'email_address' => 'superadmin@waspro.com',
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => null,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $superAdmin->peranPengguna()->attach($roles['Super Admin']->peran_id);
            $created++;
            echo "   ✓ Created 1 Super Admin (no unit)\n";
        }

        $adminsPerUnit = 1;
        $adminCount = 0;
        foreach ($units as $unit) {
            if ($adminCount >= 5) {
                break;
            }

            $admin = PenggunaSistem::create([
                'nama_lengkap' => 'Administrator '.$unit->nama_unit,
                'email_address' => 'admin.'.strtolower(str_replace(' ', '', $unit->nama_unit)).'@waspro.com',
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $admin->peranPengguna()->attach($roles['Administrator']->peran_id);
            $created++;
            $adminCount++;
        }
        echo "   ✓ Created {$adminCount} Administrators (1 per unit)\n";

        $supervisorsPerUnit = 2;
        $supervisorCount = 0;
        foreach ($units as $unit) {
            for ($i = 0; $i < $supervisorsPerUnit; $i++) {
                if ($supervisorCount >= 10) {
                    break 2;
                }

                $supervisor = PenggunaSistem::create([
                    'nama_lengkap' => 'Supervisor '.($i + 1).' - '.$unit->nama_unit,
                    'email_address' => 'supervisor'.($supervisorCount + 1).'@waspro.com',
                    'kata_sandi_hash' => Hash::make('password'),
                    'unit_id' => $unit->unit_id,
                    'aktif' => true,
                    'email_verified_at' => now(),
                ]);
                $supervisor->peranPengguna()->attach($roles['Supervisor']->peran_id);
                $created++;
                $supervisorCount++;
            }
        }
        echo "   ✓ Created {$supervisorCount} Supervisors (2 per unit)\n";

        $operatorsPerUnit = 6;
        $operatorCount = 0;
        foreach ($units as $unit) {
            for ($i = 0; $i < $operatorsPerUnit; $i++) {
                if ($operatorCount >= 30) {
                    break 2;
                }

                $operator = PenggunaSistem::create([
                    'nama_lengkap' => 'Operator '.($i + 1).' - '.$unit->nama_unit,
                    'email_address' => 'operator'.($operatorCount + 1).'@waspro.com',
                    'kata_sandi_hash' => Hash::make('password'),
                    'unit_id' => $unit->unit_id,
                    'aktif' => true,
                    'email_verified_at' => now(),
                ]);
                $operator->peranPengguna()->attach($roles['Operator']->peran_id);
                $created++;
                $operatorCount++;
            }
        }
        echo "   ✓ Created {$operatorCount} Operators (6 per unit)\n";

        $viewersPerUnit = 1;
        $viewerCount = 0;
        foreach ($units->take(4) as $unit) {
            $viewer = PenggunaSistem::create([
                'nama_lengkap' => 'Viewer - '.$unit->nama_unit,
                'email_address' => 'viewer'.($viewerCount + 1).'@waspro.com',
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $viewer->peranPengguna()->attach($roles['Viewer']->peran_id);
            $created++;
            $viewerCount++;
        }
        echo "   ✓ Created {$viewerCount} Viewers (1 per unit)\n";

        echo "   📊 Total users created: {$created}\n";
    }

    private function generateLogPenyimpananLimbah(): void
    {
        echo "\n📦 Generating Log Penyimpanan Limbah...\n";

        $existingCount = LogPenyimpananLimbah::withoutGlobalScopes()->count();
        $targetCount = 200;

        if ($existingCount >= $targetCount) {
            echo "   ✓ Logs already exist: {$existingCount}\n";

            return;
        }

        $toCreate = $targetCount - $existingCount;

        $tersimpan = (int) ($toCreate * 0.40);
        $diangkut = (int) ($toCreate * 0.35);
        $expired = $toCreate - $tersimpan - $diangkut;

        echo "   Creating {$toCreate} logs...\n";

        for ($i = 0; $i < $tersimpan; $i++) {
            $log = LogPenyimpananLimbah::factory()->tersimpan()->create();
            $this->setApprovalStatus($log, $i, $tersimpan);
        }
        echo "   ✓ Created {$tersimpan} tersimpan logs\n";

        for ($i = 0; $i < $diangkut; $i++) {
            $log = LogPenyimpananLimbah::factory()->diangkut()->create();
            $this->setApprovalStatus($log, $i, $diangkut);
        }
        echo "   ✓ Created {$diangkut} diangkut logs\n";

        for ($i = 0; $i < $expired; $i++) {
            $log = LogPenyimpananLimbah::factory()->expired()->create();
            $this->setApprovalStatus($log, $i, $expired);
        }
        echo "   ✓ Created {$expired} expired logs\n";

        $pending = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'pending')->count();
        $approved = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'approved')->count();
        $rejected = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'rejected')->count();

        echo "\n   📊 Approval Status Distribution:\n";
        echo "      - Pending: {$pending}\n";
        echo "      - Approved: {$approved}\n";
        echo "      - Rejected: {$rejected}\n";

        $critical = LogPenyimpananLimbah::withoutGlobalScopes()->where('expiry_status', 'Critical')->count();
        $warning = LogPenyimpananLimbah::withoutGlobalScopes()->where('expiry_status', 'Warning')->count();
        $safe = LogPenyimpananLimbah::withoutGlobalScopes()->where('expiry_status', 'Safe')->count();
        $expiredStatus = LogPenyimpananLimbah::withoutGlobalScopes()->where('expiry_status', 'Expired')->count();

        echo "\n   📊 Expiry Status Distribution:\n";
        echo "      - Critical: {$critical}\n";
        echo "      - Warning: {$warning}\n";
        echo "      - Safe: {$safe}\n";
        echo "      - Expired: {$expiredStatus}\n";
    }

    private function setApprovalStatus($log, $index, $total): void
    {
        $approvers = PenggunaSistem::whereHas('peranPengguna', function ($q) {
            $q->whereIn('nama_peran', ['Supervisor', 'Administrator', 'Super Admin']);
        })->where('aktif', true)->get();

        if ($approvers->isEmpty()) {
            return;
        }

        $pendingRatio = 0.50;
        $approvedRatio = 0.30;

        if ($index < $total * $pendingRatio) {
            $log->update([
                'approval_status' => 'pending',
                'approved_by' => null,
                'approved_at' => null,
                'rejected_reason' => null,
            ]);
        } elseif ($index < $total * ($pendingRatio + $approvedRatio)) {
            $approver = $approvers->random();
            $log->update([
                'approval_status' => 'approved',
                'approved_by' => $approver->user_id,
                'approved_at' => now()->subDays(rand(1, 30)),
                'rejected_reason' => null,
            ]);
        } else {
            $approver = $approvers->random();
            $reasons = [
                'Data tidak lengkap',
                'Dokumen tidak sesuai',
                'Informasi limbah tidak valid',
                'Perlu verifikasi ulang',
                'Tanggal tidak sesuai dengan dokumen',
                'Jumlah limbah tidak sesuai dengan manifest',
            ];
            $log->update([
                'approval_status' => 'rejected',
                'approved_by' => $approver->user_id,
                'approved_at' => now()->subDays(rand(1, 30)),
                'rejected_reason' => $reasons[array_rand($reasons)],
            ]);
        }
    }

    private function generateAuditLogs(): void
    {
        echo "\n📝 Generating Audit Logs...\n";

        $existingCount = AuditLog::count();

        if ($existingCount >= 100) {
            echo "   ✓ Audit logs already exist: {$existingCount}\n";

            return;
        }

        $toCreate = 150 - $existingCount;

        $creates = (int) ($toCreate * 0.20);
        $updates = (int) ($toCreate * 0.60);
        $deletes = $toCreate - $creates - $updates;

        AuditLog::factory()->createAction()->count($creates)->create();
        echo "   ✓ Created {$creates} create audit logs\n";

        AuditLog::factory()->update()->count($updates)->create();
        echo "   ✓ Created {$updates} update audit logs\n";

        AuditLog::factory()->delete()->count($deletes)->create();
        echo "   ✓ Created {$deletes} delete audit logs\n";

        echo '   📊 Total audit logs: '.AuditLog::count()."\n";
    }

    private function generateApplicationSettings(): void
    {
        echo "\n⚙️  Generating Application Settings...\n";

        $settings = [
            [
                'key' => 'app_name',
                'value' => 'WASPRO',
                'type' => 'string',
                'category' => 'general',
                'description' => 'Nama aplikasi',
                'is_active' => true,
            ],
            [
                'key' => 'limbah_expiry_days',
                'value' => '90',
                'type' => 'integer',
                'category' => 'limbah',
                'description' => 'Maksimal hari penyimpanan limbah default',
                'is_active' => true,
            ],
            [
                'key' => 'expiry_warning_days',
                'value' => '30',
                'type' => 'integer',
                'category' => 'limbah',
                'description' => 'Hari peringatan sebelum kadaluarsa',
                'is_active' => true,
            ],
            [
                'key' => 'expiry_critical_days',
                'value' => '7',
                'type' => 'integer',
                'category' => 'limbah',
                'description' => 'Hari kritis sebelum kadaluarsa',
                'is_active' => true,
            ],
            [
                'key' => 'enable_notifications',
                'value' => '1',
                'type' => 'boolean',
                'category' => 'notification',
                'description' => 'Aktifkan notifikasi sistem',
                'is_active' => true,
            ],
            [
                'key' => 'notification_email',
                'value' => 'admin@waspro.com',
                'type' => 'string',
                'category' => 'notification',
                'description' => 'Email untuk notifikasi sistem',
                'is_active' => true,
            ],
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'type' => 'boolean',
                'category' => 'system',
                'description' => 'Mode maintenance aplikasi',
                'is_active' => true,
            ],
        ];

        $created = 0;
        foreach ($settings as $setting) {
            $exists = ApplicationSetting::where('key', $setting['key'])->exists();
            if (! $exists) {
                ApplicationSetting::create($setting);
                $created++;
            }
        }

        echo "   ✓ Created/verified {$created} application settings\n";
    }
}
