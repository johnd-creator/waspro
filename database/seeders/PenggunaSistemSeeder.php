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
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada data unit dan peran
        $unitList = UnitPembangkit::all();
        $peranList = PeranPengguna::all();

        if ($unitList->isEmpty()) {
            $this->command->warn('Tidak ada data unit pembangkit. Jalankan UnitPembangkitSeeder terlebih dahulu.');

            return;
        }

        if ($peranList->isEmpty()) {
            $this->command->warn('Tidak ada data peran pengguna. Jalankan PeranPenggunaSeeder terlebih dahulu.');

            return;
        }

        // Data pengguna demo
        $users = [
            [
                'nama_lengkap' => 'Super Administrator',
                'email_address' => 'superadmin@k3system.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Super Admin'],
            ],
            [
                'nama_lengkap' => 'Administrator Jakarta',
                'email_address' => 'admin.jakarta@k3system.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Pusat')->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Admin'],
            ],
            [
                'nama_lengkap' => 'Manager Surabaya',
                'email_address' => 'manager.surabaya@k3system.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Surabaya')->first()?->unit_id ?? $unitList->skip(1)->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Manager'],
            ],
            [
                'nama_lengkap' => 'Operator Jakarta 1',
                'email_address' => 'operator1.jakarta@k3system.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Pusat')->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Operator'],
            ],
            [
                'nama_lengkap' => 'Operator Jakarta 2',
                'email_address' => 'operator2.jakarta@k3system.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Pusat')->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Operator'],
            ],
            [
                'nama_lengkap' => 'Operator Surabaya 1',
                'email_address' => 'operator1.surabaya@k3system.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Surabaya')->first()?->unit_id ?? $unitList->skip(1)->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Operator'],
            ],
            [
                'nama_lengkap' => 'Supervisor Bandung',
                'email_address' => 'supervisor.bandung@k3system.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Medan')->first()?->unit_id ?? $unitList->skip(2)->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Supervisor'],
            ],
            [
                'nama_lengkap' => 'Operator Bandung 1',
                'email_address' => 'operator1.bandung@k3system.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Medan')->first()?->unit_id ?? $unitList->skip(2)->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Operator'],
            ],
            [
                'nama_lengkap' => 'Viewer Jakarta',
                'email_address' => 'viewer.jakarta@k3system.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Pusat')->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Viewer'],
            ],
            [
                'nama_lengkap' => 'User Nonaktif',
                'email_address' => 'nonaktif@k3system.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->first()->unit_id,
                'aktif' => false,
                'peran' => ['Operator'],
            ],
        ];

        $this->command->info('Membuat pengguna sistem demo...');

        foreach ($users as $userData) {
            // Cek apakah user sudah ada
            $existingUser = PenggunaSistem::where('email_address', $userData['email_address'])->first();

            if ($existingUser) {
                $this->command->warn("User {$userData['email_address']} sudah ada, dilewati.");

                continue;
            }

            // Buat user baru
            $user = PenggunaSistem::create([
                'nama_lengkap' => $userData['nama_lengkap'],
                'email_address' => $userData['email_address'],
                'kata_sandi_hash' => Hash::make($userData['kata_sandi']),
                'unit_id' => $userData['unit_id'],
                'aktif' => $userData['aktif'],
            ]);

            // Attach peran
            $peranIds = [];
            foreach ($userData['peran'] as $peranName) {
                $peran = $peranList->where('nama_peran', $peranName)->first();
                if ($peran) {
                    $peranIds[] = $peran->peran_id;
                }
            }

            if (! empty($peranIds)) {
                $user->peranPengguna()->attach($peranIds);
            }

            $unitName = $unitList->where('unit_id', $userData['unit_id'])->first()?->nama_unit ?? 'Unknown';
            $this->command->info("✓ User {$userData['nama_lengkap']} ({$userData['email_address']}) - Unit: {$unitName}");
        }

        $this->command->info('\nSeeder PenggunaSistem selesai!');
        $this->command->info('\nInformasi Login:');
        $this->command->info('==================');
        $this->command->info('Super Admin:');
        $this->command->info('  Email: superadmin@k3system.com');
        $this->command->info('  Password: password123');
        $this->command->info('');
        $this->command->info('Admin Jakarta:');
        $this->command->info('  Email: admin.jakarta@k3system.com');
        $this->command->info('  Password: password123');
        $this->command->info('');
        $this->command->info('Operator Jakarta:');
        $this->command->info('  Email: operator1.jakarta@k3system.com');
        $this->command->info('  Password: password123');
        $this->command->info('');
        $this->command->info('Dan seterusnya...');
    }
}
