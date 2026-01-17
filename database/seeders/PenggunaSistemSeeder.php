<?php

namespace Database\Seeders;

use App\Models\PenggunaSistem;
use App\Models\PeranPengguna;
use App\Models\UnitPembangkit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSistemSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        $peranList = PeranPengguna::all();

        if ($peranList->isEmpty()) {
            $this->command->warn('Tidak ada data peran pengguna. Jalankan PeranPenggunaSeeder terlebih dahulu.');

            return;
        }

        $superAdminPeran = $peranList->where('nama_peran', 'Super Admin')->first();
        $adminPeran = $peranList->where('nama_peran', 'Administrator')->first();
        $supervisorPeran = $peranList->where('nama_peran', 'Supervisor')->first();
        $operatorPeran = $peranList->where('nama_peran', 'Operator')->first();
        $viewerPeran = $peranList->where('nama_peran', 'Viewer')->first();

        $superAdminEmail = env('SUPERADMIN_EMAIL', 'superadmin@waspro.com');
        $superAdminPassword = env('SUPERADMIN_PASSWORD', 'password123');
        $superAdminName = env('SUPERADMIN_NAME', 'Super Administrator');

        $unitList = UnitPembangkit::orderBy('nama_unit')->get();

        if ($unitList->isEmpty()) {
            $this->command->warn('Tidak ada data unit pembangkit. Jalankan UnitPembangkitSeeder terlebih dahulu.');

            return;
        }

        $users = [
            [
                'nama_lengkap' => $superAdminName,
                'email_address' => $superAdminEmail,
                'kata_sandi' => $superAdminPassword,
                'unit_id' => null,
                'aktif' => true,
                'peran' => ['Super Admin'],
            ],
            [
                'nama_lengkap' => 'Administrator Jakarta',
                'email_address' => 'admin.jakarta@waspro.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Pusat')->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Administrator'],
            ],
            [
                'nama_lengkap' => 'Manager Surabaya',
                'email_address' => 'manager.surabaya@waspro.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Surabaya')->first()?->unit_id ?? $unitList->skip(1)->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Supervisor'],
            ],
            [
                'nama_lengkap' => 'Operator Jakarta 1',
                'email_address' => 'operator1.jakarta@waspro.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Pusat')->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Operator'],
            ],
            [
                'nama_lengkap' => 'Operator Jakarta 2',
                'email_address' => 'operator2.jakarta@waspro.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Pusat')->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Operator'],
            ],
            [
                'nama_lengkap' => 'Operator Surabaya 1',
                'email_address' => 'operator1.surabaya@waspro.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Surabaya')->first()?->unit_id ?? $unitList->skip(1)->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Operator'],
            ],
            [
                'nama_lengkap' => 'Supervisor Bandung',
                'email_address' => 'supervisor.bandung@waspro.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Medan')->first()?->unit_id ?? $unitList->skip(2)->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Supervisor'],
            ],
            [
                'nama_lengkap' => 'Operator Bandung 1',
                'email_address' => 'operator1.bandung@waspro.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Medan')->first()?->unit_id ?? $unitList->skip(2)->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Operator'],
            ],
            [
                'nama_lengkap' => 'Viewer Jakarta',
                'email_address' => 'viewer.jakarta@waspro.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Pusat')->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Viewer'],
            ],
            [
                'nama_lengkap' => 'User Nonaktif',
                'email_address' => 'nonaktif@waspro.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->first()->unit_id,
                'aktif' => false,
                'peran' => ['Operator'],
            ],
        ];

        $this->command->info('Membuat pengguna sistem demo...');

        $defaultEmails = collect($users)->pluck('email_address');

        foreach ($users as $userData) {
            $payload = [
                'nama_lengkap' => $userData['nama_lengkap'],
                'kata_sandi_hash' => Hash::make($userData['kata_sandi']),
                'unit_id' => $userData['unit_id'],
                'aktif' => $userData['aktif'],
            ];

            $user = PenggunaSistem::updateOrCreate(
                ['email_address' => $userData['email_address']],
                $payload
            );

            $peranIds = [];
            foreach ($userData['peran'] as $peranName) {
                $peran = $peranList->where('nama_peran', $peranName)->first();
                if ($peran) {
                    $peranIds[] = $peran->peran_id;
                }
            }

            if (! empty($peranIds)) {
                $user->peranPengguna()->sync($peranIds);
            }

            $unitName = $userData['unit_id']
                ? ($unitList->where('unit_id', $userData['unit_id'])->first()?->nama_unit ?? 'Unknown')
                : 'Global (Super Admin)';
            $action = $user->wasRecentlyCreated ? 'dibuat' : 'diperbarui';
            $this->command->info("✓ User {$userData['nama_lengkap']} ({$userData['email_address']}) {$action} - Unit: {$unitName}");
        }

        $this->command->newLine();
        $this->command->info('Seeder PenggunaSistem selesai!');
        $this->command->newLine();
        $this->command->info('Informasi Login:');
        $this->command->info('==================');
        $this->command->info('Super Admin:');
        $this->command->info("  Email: {$superAdminEmail}");
        $this->command->info('  Password: password123');
        $this->command->line('');
        $this->command->info('Admin Jakarta:');
        $this->command->info('  Email: admin.jakarta@waspro.com');
        $this->command->info('  Password: password123');
        $this->command->line('');
        $this->command->info('Operator Jakarta:');
        $this->command->info('  Email: operator1.jakarta@waspro.com');
        $this->command->info('  Password: password123');
        $this->command->line('');
        $this->command->info('Dan seterusnya...');
    }
}
