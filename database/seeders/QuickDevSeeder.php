<?php

namespace Database\Seeders;

use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use App\Models\PeranPengguna;
use App\Models\UnitPembangkit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class QuickDevSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info("\n🚀 Quick Development Data Generator");
        $this->command->info("═══════════════════════════════════════\n");

        // Ensure roles exist
        $this->ensureRoles();

        // Ensure at least 1 unit exists
        $unit = UnitPembangkit::first() ?? UnitPembangkit::factory()->create();

        // 1. Generate 10 Jenis Limbah
        $this->command->info('🗑️  Generating 10 jenis limbah...');
        $jenisCount = 0;
        for ($i = 0; $i < 10; $i++) {
            JenisLimbah::factory()->create([
                'biaya_pengangkutan_per_kg' => fake()->randomFloat(2, 50000, 150000),
                'status_aktif' => $i < 8, // 8 active, 2 inactive
            ]);
            $jenisCount++;
        }
        $this->command->info("   ✓ Created {$jenisCount} jenis limbah (8 active, 2 inactive)\n");

        // 2. Generate 20 Users
        $this->command->info('👥 Generating 20 users...');
        $roles = [
            'Administrator' => PeranPengguna::where('nama_peran', 'Administrator')->first(),
            'Supervisor' => PeranPengguna::where('nama_peran', 'Supervisor')->first(),
            'Operator' => PeranPengguna::where('nama_peran', 'Operator')->first(),
            'Viewer' => PeranPengguna::where('nama_peran', 'Viewer')->first(),
        ];

        $userCount = 0;

        // 5 Admins
        for ($i = 0; $i < 5; $i++) {
            $user = PenggunaSistem::create([
                'nama_lengkap' => 'Admin Quick '.($i + 1),
                'email_address' => "admin.quick{$i}@waspro.com",
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $user->peranPengguna()->attach($roles['Administrator']->peran_id);
            $userCount++;
        }

        // 5 Supervisors
        for ($i = 0; $i < 5; $i++) {
            $user = PenggunaSistem::create([
                'nama_lengkap' => 'Supervisor Quick '.($i + 1),
                'email_address' => "supervisor.quick{$i}@waspro.com",
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $user->peranPengguna()->attach($roles['Supervisor']->peran_id);
            $userCount++;
        }

        // 8 Operators
        for ($i = 0; $i < 8; $i++) {
            $user = PenggunaSistem::create([
                'nama_lengkap' => 'Operator Quick '.($i + 1),
                'email_address' => "operator.quick{$i}@waspro.com",
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $user->peranPengguna()->attach($roles['Operator']->peran_id);
            $userCount++;
        }

        // 2 Viewers
        for ($i = 0; $i < 2; $i++) {
            $user = PenggunaSistem::create([
                'nama_lengkap' => 'Viewer Quick '.($i + 1),
                'email_address' => "viewer.quick{$i}@waspro.com",
                'kata_sandi_hash' => Hash::make('password'),
                'unit_id' => $unit->unit_id,
                'aktif' => true,
                'email_verified_at' => now(),
            ]);
            $user->peranPengguna()->attach($roles['Viewer']->peran_id);
            $userCount++;
        }

        $this->command->info("   ✓ Created {$userCount} users (5 admins, 5 supervisors, 8 operators, 2 viewers)\n");

        // 3. Generate 100 Logs
        $this->command->info('📦 Generating 100 logs...');
        $logCount = 0;

        // 50 Tersimpan
        for ($i = 0; $i < 50; $i++) {
            LogPenyimpananLimbah::factory()->tersimpan()->create();
            $logCount++;
        }

        // 30 Diangkut
        for ($i = 0; $i < 30; $i++) {
            LogPenyimpananLimbah::factory()->diangkut()->create();
            $logCount++;
        }

        // 20 Expired
        for ($i = 0; $i < 20; $i++) {
            LogPenyimpananLimbah::factory()->expired()->create();
            $logCount++;
        }

        $this->command->info("   ✓ Created {$logCount} logs (50 tersimpan, 30 diangkut, 20 expired)\n");

        // Summary
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('✅ Quick Dev Data Generated!');
        $this->command->info('═══════════════════════════════════════');
        $this->command->info('📊 Summary:');
        $this->command->info("   - Users: {$userCount}");
        $this->command->info("   - Jenis Limbah: {$jenisCount}");
        $this->command->info("   - Logs: {$logCount}");
        $this->command->info("\n🔑 Login Credentials:");
        $this->command->info('   Email: admin.quick0@waspro.com');
        $this->command->info("   Password: password\n");
    }

    private function ensureRoles(): void
    {
        $roles = [
            ['nama_peran' => 'Administrator', 'deskripsi' => 'Administrator', 'is_active' => true],
            ['nama_peran' => 'Supervisor', 'deskripsi' => 'Supervisor', 'is_active' => true],
            ['nama_peran' => 'Operator', 'deskripsi' => 'Operator', 'is_active' => true],
            ['nama_peran' => 'Viewer', 'deskripsi' => 'Viewer', 'is_active' => true],
        ];

        foreach ($roles as $role) {
            PeranPengguna::firstOrCreate(['nama_peran' => $role['nama_peran']], $role);
        }
    }
}
