<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use Illuminate\Database\Seeder;

class QuickDevDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("\n=== WASPRO Quick Development Data Generator ===\n");

        // Step 1: Roles
        $this->command->info('📋 Step 1/8: Generating Roles...');
        $this->call(PeranPenggunaSeeder::class);

        // Step 2: Units
        $this->command->info("\n🏢 Step 2/8: Generating Units...");
        $this->call(UnitPembangkitSeeder::class);

        // Step 3: Supporting Data
        $this->command->info("\n📚 Step 3/8: Generating Supporting Data...");
        $this->call(KarakteristikLimbahSeeder::class);
        $this->call(KategoriKegiatanSumberSeeder::class);
        $this->call(PerusahaanPenghasilSeeder::class);

        // Step 4: Jenis Limbah
        $this->command->info("\n🗑️  Step 4/8: Generating Jenis Limbah...");
        $this->call(JenisLimbahFakeSeeder::class);

        $existingJenis = JenisLimbah::count();
        if ($existingJenis < 20) {
            $this->command->info('   Adding more jenis limbah to reach 20...');
            $toCreate = 20 - $existingJenis;

            for ($i = 0; $i < $toCreate; $i++) {
                $cost = match (true) {
                    $i < 5 => fake()->randomFloat(2, 100000, 150000),
                    $i < 13 => fake()->randomFloat(2, 80000, 100000),
                    default => fake()->randomFloat(2, 50000, 80000),
                };

                JenisLimbah::factory()->create([
                    'biaya_pengangkutan_per_kg' => $cost,
                    'status_aktif' => $i < ($toCreate - 5),
                ]);
            }
            $this->command->info("   ✓ Created {$toCreate} additional jenis limbah");
        }

        // Step 5: Users
        $this->command->info("\n👥 Step 5/8: Generating Users...");
        $this->call(PenggunaSistemSeeder::class);

        $existingUsers = PenggunaSistem::count();
        if ($existingUsers < 50) {
            $this->command->info('   Adding more users to reach 50...');
            $toCreate = 50 - $existingUsers;

            $units = \App\Models\UnitPembangkit::all();
            $roles = \App\Models\PeranPengguna::all();

            for ($i = 0; $i < $toCreate; $i++) {
                $unit = $units->random();
                $role = $roles->whereIn('nama_peran', ['Operator', 'Supervisor', 'Viewer'])->random();

                $user = PenggunaSistem::create([
                    'nama_lengkap' => fake()->name(),
                    'email_address' => 'user'.($existingUsers + $i + 1).'@waspro.com',
                    'kata_sandi_hash' => \Hash::make('password'),
                    'unit_id' => $unit->unit_id,
                    'aktif' => true,
                    'email_verified_at' => now(),
                ]);

                $user->peranPengguna()->attach($role->peran_id);
            }
            $this->command->info("   ✓ Created {$toCreate} additional users");
        }

        // Step 6: Log Penyimpanan Limbah
        $this->command->info("\n📦 Step 6/8: Generating Log Penyimpanan Limbah...");

        $existingLogs = LogPenyimpananLimbah::withoutGlobalScopes()->count();
        if ($existingLogs < 200) {
            $toCreate = 200 - $existingLogs;
            $this->command->info("   Creating {$toCreate} logs...");

            $tersimpan = (int) ($toCreate * 0.40);
            $diangkut = (int) ($toCreate * 0.35);
            $expired = $toCreate - $tersimpan - $diangkut;

            $this->command->info("   - Tersimpan: {$tersimpan}");
            for ($i = 0; $i < $tersimpan; $i++) {
                LogPenyimpananLimbah::factory()->tersimpan()->create();
            }

            $this->command->info("   - Diangkut: {$diangkut}");
            for ($i = 0; $i < $diangkut; $i++) {
                LogPenyimpananLimbah::factory()->diangkut()->create();
            }

            $this->command->info("   - Expired: {$expired}");
            for ($i = 0; $i < $expired; $i++) {
                LogPenyimpananLimbah::factory()->expired()->create();
            }

            $this->command->info("   ✓ Created {$toCreate} logs");
        }

        // Step 7: Update Approval Statuses
        $this->command->info("\n✅ Step 7/8: Updating Approval Statuses...");

        $approvers = PenggunaSistem::whereHas('peranPengguna', function ($q) {
            $q->whereIn('nama_peran', ['Supervisor', 'Administrator', 'Super Admin']);
        })->where('aktif', true)->get();

        if ($approvers->isNotEmpty()) {
            $allLogs = LogPenyimpananLimbah::withoutGlobalScopes()->get();
            $processed = 0;

            foreach ($allLogs as $log) {
                $rand = rand(1, 100);

                if ($rand <= 50) {
                    $log->update([
                        'approval_status' => 'pending',
                        'approved_by' => null,
                        'approved_at' => null,
                        'rejected_reason' => null,
                    ]);
                } elseif ($rand <= 80) {
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
                    ];
                    $log->update([
                        'approval_status' => 'rejected',
                        'approved_by' => $approver->user_id,
                        'approved_at' => now()->subDays(rand(1, 30)),
                        'rejected_reason' => $reasons[array_rand($reasons)],
                    ]);
                }

                $processed++;
            }

            $this->command->info("   ✓ Updated {$processed} logs");
        }

        // Step 8: Audit Logs
        $this->command->info("\n📝 Step 8/8: Generating Audit Logs...");

        $existingAudits = AuditLog::count();
        if ($existingAudits < 150) {
            $toCreate = 150 - $existingAudits;

            $creates = (int) ($toCreate * 0.20);
            $updates = (int) ($toCreate * 0.60);
            $deletes = $toCreate - $creates - $updates;

            AuditLog::factory()->createAction()->count($creates)->create();
            AuditLog::factory()->update()->count($updates)->create();
            AuditLog::factory()->delete()->count($deletes)->create();

            $this->command->info("   ✓ Created {$toCreate} audit logs");
        }

        // Application Settings
        $this->call(ApplicationSettingSeeder::class);

        // Summary
        $this->command->info("\n✅ All data generated successfully!");
        $this->command->info("===========================================\n");

        $this->command->info('📊 Final Summary:');
        $this->command->info('   - Roles: '.\App\Models\PeranPengguna::count());
        $this->command->info('   - Units: '.\App\Models\UnitPembangkit::count());
        $this->command->info('   - Users: '.PenggunaSistem::count());
        $this->command->info('   - Jenis Limbah: '.JenisLimbah::count());
        $this->command->info('   - Log Penyimpanan: '.LogPenyimpananLimbah::withoutGlobalScopes()->count());
        $this->command->info('   - Audit Logs: '.AuditLog::count());

        $pending = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'pending')->count();
        $approved = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'approved')->count();
        $rejected = LogPenyimpananLimbah::withoutGlobalScopes()->where('approval_status', 'rejected')->count();

        $this->command->info("\n   Approval Status:");
        $this->command->info("   - Pending: {$pending}");
        $this->command->info("   - Approved: {$approved}");
        $this->command->info("   - Rejected: {$rejected}");

        $this->command->info("\n");
    }
}
