<?php

// WASPRO Development Data Generator
// Run with: php artisan tinker < database/scripts/generate_dev_data.php

echo "\n=== WASPRO Development Data Generator ===\n\n";

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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

DB::beginTransaction();

try {
    // 1. Generate Roles
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

    // 2. Generate Supporting Data
    echo "\n🏢 Generating Supporting Data...\n";

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
    echo "   ✓ Created/verified 5 units\n";

    $karakteristikCount = KarakteristikLimbah::count();
    if ($karakteristikCount < 5) {
        KarakteristikLimbah::factory()->count(5 - $karakteristikCount)->create();
        echo '   ✓ Created '.(5 - $karakteristikCount)." karakteristik limbah\n";
    }

    $kategoriCount = KategoriKegiatanSumber::count();
    if ($kategoriCount < 5) {
        KategoriKegiatanSumber::factory()->count(5 - $kategoriCount)->create();
        echo '   ✓ Created '.(5 - $kategoriCount)." kategori kegiatan\n";
    }

    $perusahaanCount = PerusahaanPenghasil::count();
    if ($perusahaanCount < 10) {
        PerusahaanPenghasil::factory()->count(10 - $perusahaanCount)->create();
        echo '   ✓ Created '.(10 - $perusahaanCount)." perusahaan\n";
    }

    // 3. Generate Jenis Limbah
    echo "\n🗑️  Generating Jenis Limbah...\n";

    $existingJenis = JenisLimbah::count();
    if ($existingJenis < 20) {
        $toCreate = 20 - $existingJenis;

        // High cost
        for ($i = 0; $i < 5; $i++) {
            JenisLimbah::factory()->create([
                'biaya_pengangkutan_per_kg' => fake()->randomFloat(2, 100000, 150000),
                'status_aktif' => true,
            ]);
        }

        // Medium cost
        for ($i = 0; $i < 8; $i++) {
            JenisLimbah::factory()->create([
                'biaya_pengangkutan_per_kg' => fake()->randomFloat(2, 80000, 100000),
                'status_aktif' => true,
            ]);
        }

        // Low cost
        for ($i = 0; $i < 7; $i++) {
            JenisLimbah::factory()->create([
                'biaya_pengangkutan_per_kg' => fake()->randomFloat(2, 50000, 80000),
                'status_aktif' => $i < 2,
            ]);
        }

        echo "   ✓ Created 20 jenis limbah (5 high, 8 medium, 7 low cost)\n";
    }

    // 4. Generate Users
    echo "\n👥 Generating Users with Roles...\n";

    $roleModels = [
        'Super Admin' => PeranPengguna::where('nama_peran', 'Super Admin')->first(),
        'Administrator' => PeranPengguna::where('nama_peran', 'Administrator')->first(),
        'Supervisor' => PeranPengguna::where('nama_peran', 'Supervisor')->first(),
        'Operator' => PeranPengguna::where('nama_peran', 'Operator')->first(),
        'Viewer' => PeranPengguna::where('nama_peran', 'Viewer')->first(),
    ];

    $allUnits = UnitPembangkit::all();
    $created = 0;

    // Super Admin
    if (! PenggunaSistem::whereHas('peranPengguna', fn ($q) => $q->where('nama_peran', 'Super Admin'))->exists()) {
        $superAdmin = PenggunaSistem::create([
            'nama_lengkap' => 'Super Administrator',
            'email_address' => 'superadmin@waspro.com',
            'kata_sandi_hash' => Hash::make('password'),
            'unit_id' => null,
            'aktif' => true,
            'email_verified_at' => now(),
        ]);
        $superAdmin->peranPengguna()->attach($roleModels['Super Admin']->peran_id);
        $created++;
    }
    echo "   ✓ Created 1 Super Admin\n";

    // Administrators (1 per unit, 5 total)
    $adminCount = 0;
    foreach ($allUnits as $unit) {
        if ($adminCount >= 5) {
            break;
        }

        $admin = PenggunaSistem::create([
            'nama_lengkap' => 'Administrator '.$unit->nama_unit,
            'email_address' => 'admin.'.$adminCount.'@waspro.com',
            'kata_sandi_hash' => Hash::make('password'),
            'unit_id' => $unit->unit_id,
            'aktif' => true,
            'email_verified_at' => now(),
        ]);
        $admin->peranPengguna()->attach($roleModels['Administrator']->peran_id);
        $created++;
        $adminCount++;
    }
    echo "   ✓ Created {$adminCount} Administrators\n";

    // Supervisors (2 per unit, 10 total)
    $supervisorCount = 0;
    foreach ($allUnits as $unit) {
        for ($i = 0; $i < 2; $i++) {
            if ($supervisorCount >= 10) {
                break 2;
            }

            $supervisor = PenggunaSistem::create([
                'nama_lengkap' => 'Supervisor '.($supervisorCount + 1),
                'email_address' => 'supervisor'.($supervisorCount + 1).'@waspro.com',
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $supervisor->peranPengguna()->attach($roleModels['Supervisor']->peran_id);
            $created++;
            $supervisorCount++;
        }
    }
    echo "   ✓ Created {$supervisorCount} Supervisors\n";

    // Operators (6 per unit, 30 total)
    $operatorCount = 0;
    foreach ($allUnits as $unit) {
        for ($i = 0; $i < 6; $i++) {
            if ($operatorCount >= 30) {
                break 2;
            }

            $operator = PenggunaSistem::create([
                'nama_lengkap' => 'Operator '.($operatorCount + 1),
                'email_address' => 'operator'.($operatorCount + 1).'@waspro.com',
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $operator->peranPengguna()->attach($roleModels['Operator']->peran_id);
            $created++;
            $operatorCount++;
        }
    }
    echo "   ✓ Created {$operatorCount} Operators\n";

    // Viewers (1 per unit for 4 units, 4 total)
    $viewerCount = 0;
    foreach ($allUnits->take(4) as $unit) {
        $viewer = PenggunaSistem::create([
            'nama_lengkap' => 'Viewer '.($viewerCount + 1),
            'email_address' => 'viewer'.($viewerCount + 1).'@waspro.com',
            'kata_sandi_hash' => Hash::make('password'),
            'unit_id' => $unit->unit_id,
            'aktif' => true,
            'email_verified_at' => now(),
        ]);
        $viewer->peranPengguna()->attach($roleModels['Viewer']->peran_id);
        $created++;
        $viewerCount++;
    }
    echo "   ✓ Created {$viewerCount} Viewers\n";
    echo "   📊 Total users created: {$created}\n";

    // 5. Generate Log Penyimpanan Limbah
    echo "\n📦 Generating Log Penyimpanan Limbah...\n";

    $existingLogs = LogPenyimpananLimbah::withoutGlobalScopes()->count();
    if ($existingLogs < 200) {
        echo "   Creating logs (this may take a while)...\n";

        // Tersimpan (80 logs)
        for ($i = 0; $i < 80; $i++) {
            LogPenyimpananLimbah::factory()->tersimpan()->create();
            if ($i % 20 == 0) {
                echo "   ... {$i}/80 tersimpan\n";
            }
        }
        echo "   ✓ Created 80 tersimpan logs\n";

        // Diangkut (70 logs)
        for ($i = 0; $i < 70; $i++) {
            LogPenyimpananLimbah::factory()->diangkut()->create();
            if ($i % 20 == 0) {
                echo "   ... {$i}/70 diangkut\n";
            }
        }
        echo "   ✓ Created 70 diangkut logs\n";

        // Expired (50 logs)
        for ($i = 0; $i < 50; $i++) {
            LogPenyimpananLimbah::factory()->expired()->create();
            if ($i % 20 == 0) {
                echo "   ... {$i}/50 expired\n";
            }
        }
        echo "   ✓ Created 50 expired logs\n";
    }

    // 6. Update approval statuses
    echo "\n✅ Updating approval statuses...\n";

    $approvers = PenggunaSistem::whereHas('peranPengguna', function ($q) {
        $q->whereIn('nama_peran', ['Supervisor', 'Administrator', 'Super Admin']);
    })->where('aktif', true)->get();

    if ($approvers->isNotEmpty()) {
        $allLogs = LogPenyimpananLimbah::withoutGlobalScopes()->get();
        $total = $allLogs->count();
        $processed = 0;

        foreach ($allLogs as $log) {
            $rand = rand(1, 100);

            if ($rand <= 50) {
                // 50% pending
                $log->update([
                    'approval_status' => 'pending',
                    'approved_by' => null,
                    'approved_at' => null,
                    'rejected_reason' => null,
                ]);
            } elseif ($rand <= 80) {
                // 30% approved
                $approver = $approvers->random();
                $log->update([
                    'approval_status' => 'approved',
                    'approved_by' => $approver->user_id,
                    'approved_at' => now()->subDays(rand(1, 30)),
                    'rejected_reason' => null,
                ]);
            } else {
                // 20% rejected
                $approver = $approvers->random();
                $reasons = [
                    'Data tidak lengkap',
                    'Dokumen tidak sesuai',
                    'Informasi limbah tidak valid',
                    'Perlu verifikasi ulang',
                ];
                $log->update([
                    'approval_status' => 'rejected',
                    'approved_by' => $approver->user_id,
                    'approved_at' => now()->subDays(rand(1, 30)),
                    'rejected_reason' => $reasons[array_rand($reasons)],
                ]);
            }

            $processed++;
            if ($processed % 50 == 0) {
                echo "   ... processed {$processed}/{$total} logs\n";
            }
        }

        echo "   ✓ Updated approval statuses for {$total} logs\n";
    }

    // 7. Generate Audit Logs
    echo "\n📝 Generating Audit Logs...\n";

    $existingAudits = AuditLog::count();
    if ($existingAudits < 150) {
        AuditLog::factory()->createAction()->count(30)->create();
        AuditLog::factory()->update()->count(90)->create();
        AuditLog::factory()->delete()->count(30)->create();
        echo "   ✓ Created 150 audit logs\n";
    }

    // 8. Generate Application Settings
    echo "\n⚙️  Generating Application Settings...\n";

    $settings = [
        ['key' => 'app_name', 'value' => 'WASPRO', 'type' => 'string', 'category' => 'general', 'description' => 'Nama aplikasi', 'is_active' => true],
        ['key' => 'limbah_expiry_days', 'value' => '90', 'type' => 'integer', 'category' => 'limbah', 'description' => 'Maksimal hari penyimpanan limbah default', 'is_active' => true],
        ['key' => 'expiry_warning_days', 'value' => '30', 'type' => 'integer', 'category' => 'limbah', 'description' => 'Hari peringatan sebelum kadaluarsa', 'is_active' => true],
        ['key' => 'expiry_critical_days', 'value' => '7', 'type' => 'integer', 'category' => 'limbah', 'description' => 'Hari kritis sebelum kadaluarsa', 'is_active' => true],
        ['key' => 'enable_notifications', 'value' => '1', 'type' => 'boolean', 'category' => 'notification', 'description' => 'Aktifkan notifikasi sistem', 'is_active' => true],
    ];

    foreach ($settings as $setting) {
        ApplicationSetting::firstOrCreate(['key' => $setting['key']], $setting);
    }
    echo "   ✓ Created/verified application settings\n";

    DB::commit();

    echo "\n✅ All data generated successfully!\n";
    echo "===========================================\n\n";

    // Summary
    echo "📊 Summary:\n";
    echo '   - Roles: '.PeranPengguna::count()."\n";
    echo '   - Units: '.UnitPembangkit::count()."\n";
    echo '   - Users: '.PenggunaSistem::count()."\n";
    echo '   - Jenis Limbah: '.JenisLimbah::count()."\n";
    echo '   - Log Penyimpanan: '.LogPenyimpananLimbah::withoutGlobalScopes()->count()."\n";
    echo '   - Audit Logs: '.AuditLog::count()."\n";
    echo '   - Application Settings: '.ApplicationSetting::count()."\n";
    echo "\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ Error: ".$e->getMessage()."\n";
    echo 'File: '.$e->getFile().':'.$e->getLine()."\n";
    throw $e;
}
