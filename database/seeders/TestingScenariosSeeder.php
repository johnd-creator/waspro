<?php

namespace Database\Seeders;

use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use App\Models\PeranPengguna;
use App\Models\UnitPembangkit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestingScenariosSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("\n=== WASPRO Testing Scenarios Data Generator ===\n");

        DB::beginTransaction();

        try {
            $this->scenario1_ApprovalWorkflow();
            $this->scenario2_ExpiryNotification();
            $this->scenario3_MultiUnitAccess();
            $this->scenario4_AuditTrail();
            $this->scenario5_DocumentManagement();
            $this->scenario6_CostTracking();
            $this->scenario7_BulkOperations();

            DB::commit();

            $this->command->info("\n✅ All testing scenarios generated successfully!");
            $this->command->info("===========================================\n");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("\n❌ Error: ".$e->getMessage());
            $this->command->error('File: '.$e->getFile().':'.$e->getLine());
            throw $e;
        }
    }

    private function scenario1_ApprovalWorkflow(): void
    {
        $this->command->info('📋 Scenario 1: Approval Workflow Testing');

        // Get or create a unit
        $unit = UnitPembangkit::first() ?? UnitPembangkit::factory()->create();

        // Get roles
        $supervisorRole = PeranPengguna::where('nama_peran', 'Supervisor')->first();
        $operatorRole = PeranPengguna::where('nama_peran', 'Operator')->first();

        // Create 1 Supervisor
        $supervisor = PenggunaSistem::create([
            'nama_lengkap' => 'Supervisor Testing Approval',
            'email_address' => 'supervisor.approval@test.waspro.com',
            'kata_sandi_hash' => Hash::make('password'),
            'unit_id' => $unit->unit_id,
            'aktif' => true,
            'email_verified_at' => now(),
        ]);
        $supervisor->peranPengguna()->attach($supervisorRole->peran_id);

        // Create 3 Operators
        $operators = [];
        for ($i = 1; $i <= 3; $i++) {
            $operator = PenggunaSistem::create([
                'nama_lengkap' => "Operator Testing Approval {$i}",
                'email_address' => "operator.approval{$i}@test.waspro.com",
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $operator->peranPengguna()->attach($operatorRole->peran_id);
            $operators[] = $operator;
        }

        // Create 30 pending logs
        for ($i = 0; $i < 30; $i++) {
            LogPenyimpananLimbah::factory()->create([
                'unit_id' => $unit->unit_id,
                'user_id' => $operators[array_rand($operators)]->user_id,
                'approval_status' => 'pending',
                'approved_by' => null,
                'approved_at' => null,
                'rejected_reason' => null,
            ]);
        }

        // Create 15 approved logs
        for ($i = 0; $i < 15; $i++) {
            LogPenyimpananLimbah::factory()->create([
                'unit_id' => $unit->unit_id,
                'user_id' => $operators[array_rand($operators)]->user_id,
                'approval_status' => 'approved',
                'approved_by' => $supervisor->user_id,
                'approved_at' => now()->subDays(rand(1, 30)),
                'rejected_reason' => null,
            ]);
        }

        // Create 10 rejected logs
        $rejectionReasons = [
            'Data tidak lengkap',
            'Dokumen tidak sesuai',
            'Informasi limbah tidak valid',
            'Perlu verifikasi ulang',
            'Tanggal tidak sesuai dengan dokumen',
        ];

        for ($i = 0; $i < 10; $i++) {
            LogPenyimpananLimbah::factory()->create([
                'unit_id' => $unit->unit_id,
                'user_id' => $operators[array_rand($operators)]->user_id,
                'approval_status' => 'rejected',
                'approved_by' => $supervisor->user_id,
                'approved_at' => now()->subDays(rand(1, 30)),
                'rejected_reason' => $rejectionReasons[array_rand($rejectionReasons)],
            ]);
        }

        $this->command->info('   ✓ Created 1 Supervisor, 3 Operators');
        $this->command->info('   ✓ Created 30 pending, 15 approved, 10 rejected logs');
        $this->command->info("   ✓ All in unit: {$unit->nama_unit}\n");
    }

    private function scenario2_ExpiryNotification(): void
    {
        $this->command->info('📅 Scenario 2: Expiry Notification Testing');

        $units = UnitPembangkit::take(3)->get();
        if ($units->count() < 3) {
            $needed = 3 - $units->count();
            for ($i = 0; $i < $needed; $i++) {
                $units->push(UnitPembangkit::factory()->create());
            }
        }

        $now = now();

        // 20 Critical logs (1-7 days to expiry)
        for ($i = 0; $i < 20; $i++) {
            $daysToExpiry = rand(1, 7);
            $expiryDate = $now->copy()->addDays($daysToExpiry);
            $entryDate = $expiryDate->copy()->subDays(90);

            LogPenyimpananLimbah::factory()->create([
                'unit_id' => $units->random()->unit_id,
                'tanggal_limbah_masuk' => $entryDate,
                'tanggal_kadaluarsa' => $expiryDate,
                'expiry_status' => 'Critical',
                'status_log' => 'Tersimpan',
            ]);
        }

        // 30 Warning logs (8-30 days to expiry)
        for ($i = 0; $i < 30; $i++) {
            $daysToExpiry = rand(8, 30);
            $expiryDate = $now->copy()->addDays($daysToExpiry);
            $entryDate = $expiryDate->copy()->subDays(90);

            LogPenyimpananLimbah::factory()->create([
                'unit_id' => $units->random()->unit_id,
                'tanggal_limbah_masuk' => $entryDate,
                'tanggal_kadaluarsa' => $expiryDate,
                'expiry_status' => 'Warning',
                'status_log' => 'Tersimpan',
            ]);
        }

        // 15 Expired logs
        for ($i = 0; $i < 15; $i++) {
            LogPenyimpananLimbah::factory()->expired()->create([
                'unit_id' => $units->random()->unit_id,
            ]);
        }

        // 25 Safe logs (>30 days to expiry)
        for ($i = 0; $i < 25; $i++) {
            $daysToExpiry = rand(31, 90);
            $expiryDate = $now->copy()->addDays($daysToExpiry);
            $entryDate = $expiryDate->copy()->subDays(90);

            LogPenyimpananLimbah::factory()->create([
                'unit_id' => $units->random()->unit_id,
                'tanggal_limbah_masuk' => $entryDate,
                'tanggal_kadaluarsa' => $expiryDate,
                'expiry_status' => 'Safe',
                'status_log' => 'Tersimpan',
            ]);
        }

        $this->command->info('   ✓ Created 20 Critical, 30 Warning, 15 Expired, 25 Safe logs');
        $this->command->info("   ✓ Distributed across {$units->count()} units\n");
    }

    private function scenario3_MultiUnitAccess(): void
    {
        $this->command->info('🏢 Scenario 3: Multi-Unit Access Control Testing');

        // Ensure we have 5 units
        $existingUnits = UnitPembangkit::count();
        if ($existingUnits < 5) {
            $needed = 5 - $existingUnits;
            for ($i = 0; $i < $needed; $i++) {
                UnitPembangkit::factory()->create();
            }
        }

        $units = UnitPembangkit::take(5)->get();

        // Get roles
        $superAdminRole = PeranPengguna::where('nama_peran', 'Super Admin')->first();
        $adminRole = PeranPengguna::where('nama_peran', 'Administrator')->first();
        $supervisorRole = PeranPengguna::where('nama_peran', 'Supervisor')->first();
        $operatorRole = PeranPengguna::where('nama_peran', 'Operator')->first();

        // Create Super Admin
        $superAdmin = PenggunaSistem::create([
            'nama_lengkap' => 'Super Admin Testing MultiUnit',
            'email_address' => 'superadmin.multiunit@test.waspro.com',
            'kata_sandi_hash' => Hash::make('password'),
            'unit_id' => null,
            'aktif' => true,
            'email_verified_at' => now(),
        ]);
        $superAdmin->peranPengguna()->attach($superAdminRole->peran_id);

        $totalUsers = 1;
        $totalLogs = 0;

        // For each unit, create users and logs
        foreach ($units as $index => $unit) {
            // 1 Administrator
            $admin = PenggunaSistem::create([
                'nama_lengkap' => "Administrator Testing Unit {$unit->nama_unit}",
                'email_address' => "admin.multiunit{$index}@test.waspro.com",
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $admin->peranPengguna()->attach($adminRole->peran_id);
            $totalUsers++;

            // 1 Supervisor
            $supervisor = PenggunaSistem::create([
                'nama_lengkap' => "Supervisor Testing Unit {$unit->nama_unit}",
                'email_address' => "supervisor.multiunit{$index}@test.waspro.com",
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $supervisor->peranPengguna()->attach($supervisorRole->peran_id);
            $totalUsers++;

            // 2 Operators
            $operators = [];
            for ($i = 1; $i <= 2; $i++) {
                $operator = PenggunaSistem::create([
                    'nama_lengkap' => "Operator {$i} Testing Unit {$unit->nama_unit}",
                    'email_address' => "operator{$i}.multiunit{$index}@test.waspro.com",
                    'kata_sandi_hash' => Hash::make('password'),
                    'unit_id' => $unit->unit_id,
                    'aktif' => true,
                    'email_verified_at' => now(),
                ]);
                $operator->peranPengguna()->attach($operatorRole->peran_id);
                $operators[] = $operator;
                $totalUsers++;
            }

            // 50 logs
            for ($i = 0; $i < 50; $i++) {
                LogPenyimpananLimbah::factory()->create([
                    'unit_id' => $unit->unit_id,
                    'user_id' => $operators[array_rand($operators)]->user_id,
                ]);
                $totalLogs++;
            }
        }

        $this->command->info('   ✓ Created 1 Super Admin (global access)');
        $this->command->info("   ✓ Created {$totalUsers} users total (1 Admin, 1 Supervisor, 2 Operators per unit)");
        $this->command->info("   ✓ Created {$totalLogs} logs (50 per unit)");
        $this->command->info("   ✓ Across 5 units\n");
    }

    private function scenario4_AuditTrail(): void
    {
        $this->command->info('📝 Scenario 4: Audit Trail Testing');

        // Create 5 users with various roles
        $roles = PeranPengguna::whereIn('nama_peran', ['Administrator', 'Supervisor', 'Operator'])->get();
        $unit = UnitPembangkit::first() ?? UnitPembangkit::factory()->create();

        $users = [];
        for ($i = 1; $i <= 5; $i++) {
            $role = $roles->random();
            $user = PenggunaSistem::create([
                'nama_lengkap' => "User Audit Testing {$i}",
                'email_address' => "user.audit{$i}@test.waspro.com",
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $user->peranPengguna()->attach($role->peran_id);
            $users[] = $user;
        }

        // Generate audit logs using the factory
        $createLogs = 0;
        $updateLogs = 0;
        $deleteLogs = 0;

        // 10 create operations
        for ($i = 0; $i < 10; $i++) {
            \App\Models\AuditLog::factory()->createAction()->create([
                'user_id' => $users[array_rand($users)]->user_id,
            ]);
            $createLogs++;
        }

        // 10 update operations
        for ($i = 0; $i < 10; $i++) {
            \App\Models\AuditLog::factory()->update()->create([
                'user_id' => $users[array_rand($users)]->user_id,
            ]);
            $updateLogs++;
        }

        // 5 delete operations
        for ($i = 0; $i < 5; $i++) {
            \App\Models\AuditLog::factory()->delete()->create([
                'user_id' => $users[array_rand($users)]->user_id,
            ]);
            $deleteLogs++;
        }

        $this->command->info('   ✓ Created 5 users with various roles');
        $this->command->info("   ✓ Generated {$createLogs} create, {$updateLogs} update, {$deleteLogs} delete audit logs");
        $this->command->info("   ✓ All audit logs have user_id, old_value, new_value, ip_address, user_agent\n");
    }

    private function scenario5_DocumentManagement(): void
    {
        $this->command->info('📄 Scenario 5: Document Management Testing');

        // 20 logs with documents
        for ($i = 0; $i < 20; $i++) {
            $size = rand(80000, 5000000); // 80KB to 5MB

            LogPenyimpananLimbah::factory()->create([
                'dokumen_path' => 'documents/testing/'.fake()->uuid().'.pdf',
                'dokumen_original_name' => 'Dokumen Limbah '.fake()->words(3, true).'.pdf',
                'dokumen_mime' => 'application/pdf',
                'dokumen_size' => $size,
                'dokumen_uploaded_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // 10 logs without documents
        for ($i = 0; $i < 10; $i++) {
            LogPenyimpananLimbah::factory()->create([
                'dokumen_path' => null,
                'dokumen_original_name' => null,
                'dokumen_mime' => null,
                'dokumen_size' => null,
                'dokumen_uploaded_at' => null,
            ]);
        }

        $this->command->info('   ✓ Created 20 logs with PDF documents (80KB - 5MB)');
        $this->command->info("   ✓ Created 10 logs without documents\n");
    }

    private function scenario6_CostTracking(): void
    {
        $this->command->info('💰 Scenario 6: Cost Tracking Testing');

        // Create jenis limbah with different cost ranges
        $highCost = [];
        $mediumCost = [];
        $lowCost = [];

        // 5 High cost (>100k)
        for ($i = 0; $i < 5; $i++) {
            $jenis = JenisLimbah::factory()->create([
                'biaya_pengangkutan_per_kg' => fake()->randomFloat(2, 100000, 150000),
                'status_aktif' => true,
            ]);
            $highCost[] = $jenis;
        }

        // 5 Medium cost (80k-100k)
        for ($i = 0; $i < 5; $i++) {
            $jenis = JenisLimbah::factory()->create([
                'biaya_pengangkutan_per_kg' => fake()->randomFloat(2, 80000, 100000),
                'status_aktif' => true,
            ]);
            $mediumCost[] = $jenis;
        }

        // 5 Low cost (<80k)
        for ($i = 0; $i < 5; $i++) {
            $jenis = JenisLimbah::factory()->create([
                'biaya_pengangkutan_per_kg' => fake()->randomFloat(2, 50000, 80000),
                'status_aktif' => true,
            ]);
            $lowCost[] = $jenis;
        }

        $allJenis = array_merge($highCost, $mediumCost, $lowCost);

        // Create 50 logs using these jenis limbah
        for ($i = 0; $i < 50; $i++) {
            $jenis = $allJenis[array_rand($allJenis)];

            LogPenyimpananLimbah::factory()->create([
                'kode_limbah' => $jenis->kode_limbah,
                'jumlah_limbah_masuk' => fake()->randomFloat(2, 1, 100),
            ]);
        }

        $this->command->info('   ✓ Created 5 high-cost (>100k), 5 medium-cost (80k-100k), 5 low-cost (<80k) jenis limbah');
        $this->command->info('   ✓ Created 50 logs using these jenis limbah');
        $this->command->info("   ✓ Cost calculation ready for testing\n");
    }

    private function scenario7_BulkOperations(): void
    {
        $this->command->info('📦 Scenario 7: Bulk Operations Testing');

        // Get 5 units
        $units = UnitPembangkit::take(5)->get();
        if ($units->count() < 5) {
            $needed = 5 - $units->count();
            for ($i = 0; $i < $needed; $i++) {
                $units->push(UnitPembangkit::factory()->create());
            }
            $units = UnitPembangkit::take(5)->get();
        }

        // Get roles
        $supervisorRole = PeranPengguna::where('nama_peran', 'Supervisor')->first();
        $adminRole = PeranPengguna::where('nama_peran', 'Administrator')->first();

        // Create 10 users (5 Supervisors, 5 Administrators)
        $bulkUsers = [];
        foreach ($units as $index => $unit) {
            // Supervisor
            $supervisor = PenggunaSistem::create([
                'nama_lengkap' => "Supervisor Bulk Testing {$unit->nama_unit}",
                'email_address' => "supervisor.bulk{$index}@test.waspro.com",
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $supervisor->peranPengguna()->attach($supervisorRole->peran_id);
            $bulkUsers[] = $supervisor;

            // Administrator
            $admin = PenggunaSistem::create([
                'nama_lengkap' => "Administrator Bulk Testing {$unit->nama_unit}",
                'email_address' => "admin.bulk{$index}@test.waspro.com",
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $admin->peranPengguna()->attach($adminRole->peran_id);
            $bulkUsers[] = $admin;
        }

        // Create 100 logs total (20 per unit)
        $totalLogs = 0;
        foreach ($units as $unit) {
            for ($i = 0; $i < 20; $i++) {
                LogPenyimpananLimbah::factory()->create([
                    'unit_id' => $unit->unit_id,
                    'approval_status' => 'pending',
                ]);
                $totalLogs++;
            }
        }

        $this->command->info('   ✓ Created 10 users (5 Supervisors, 5 Administrators)');
        $this->command->info("   ✓ Created {$totalLogs} logs (20 per unit across 5 units)");
        $this->command->info("   ✓ All logs are pending - ready for bulk approve/reject/delete testing\n");
    }
}
