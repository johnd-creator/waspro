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
                'email_address' => 'superadmin@waspro.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Super Admin'],
            ],
            [
                'nama_lengkap' => 'Administrator Jakarta',
                'email_address' => 'admin.jakarta@waspro.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Pusat')->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Admin'],
            ],
            [
                'nama_lengkap' => 'Manager Surabaya',
                'email_address' => 'manager.surabaya@waspro.com',
                'kata_sandi' => 'password123',
                'unit_id' => $unitList->where('nama_unit', 'Unit Pembangkit Surabaya')->first()?->unit_id ?? $unitList->skip(1)->first()?->unit_id ?? $unitList->first()->unit_id,
                'aktif' => true,
                'peran' => ['Manager'],
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

            $unitName = $unitList->where('unit_id', $userData['unit_id'])->first()?->nama_unit ?? 'Unknown';
            $action = $user->wasRecentlyCreated ? 'dibuat' : 'diperbarui';
            $this->command->info("✓ User {$userData['nama_lengkap']} ({$userData['email_address']}) {$action} - Unit: {$unitName}");
        }

        // Tambahan pengguna via factory untuk kebutuhan pengujian jika belum ada data selain default
        $hasAdditionalUsers = PenggunaSistem::whereNotIn('email_address', $defaultEmails)->exists();

        if (! $hasAdditionalUsers) {
            $this->command->info('Menambahkan pengguna tambahan menggunakan factory...');

            PenggunaSistem::factory()
                ->count(5)
                ->state(fn () => [
                    'unit_id' => $unitList->random()->unit_id,
                    'kata_sandi_hash' => Hash::make('password123'),
                ])
                ->create()
                ->each(function (PenggunaSistem $user) use ($peranList) {
                    $peran = $peranList->random();
                    $user->peranPengguna()->sync([$peran->peran_id]);
                    $this->command?->info("  • {$user->nama_lengkap} ({$user->email_address}) sebagai {$peran->nama_peran}");
                });
        }

        $this->command->newLine();
        $this->command->info('Seeder PenggunaSistem selesai!');
        $this->command->newLine();
        $this->command->info('Informasi Login:');
        $this->command->info('==================');
        $this->command->info('Super Admin:');
        $this->command->info('  Email: superadmin@waspro.com');
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
