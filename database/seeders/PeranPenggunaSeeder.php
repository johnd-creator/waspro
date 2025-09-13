<?php

namespace Database\Seeders;

use App\Models\PeranPengguna;
use Illuminate\Database\Seeder;

class PeranPenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin - Akses penuh ke semua unit dan sistem
        PeranPengguna::firstOrCreate(
            ['nama_peran' => 'Super Admin'],
            [
                'deskripsi' => 'Super Administrator dengan akses penuh ke semua unit pembangkit dan sistem',
                'is_active' => true,
            ]
        );

        // Administrator - Akses penuh tapi terbatas pada unit sendiri
        PeranPengguna::firstOrCreate(
            ['nama_peran' => 'Administrator'],
            [
                'deskripsi' => 'Administrator unit dengan akses penuh ke semua menu kecuali master data unit lain',
                'is_active' => true,
            ]
        );

        // Operator - Hanya bisa input dan edit log limbah untuk unit sendiri
        PeranPengguna::firstOrCreate(
            ['nama_peran' => 'Operator'],
            [
                'deskripsi' => 'Operator unit yang hanya dapat menginput dan mengedit log penyimpanan limbah untuk unit sendiri',
                'is_active' => true,
            ]
        );

        // Supervisor - Dapat menyetujui pengangkutan limbah yang dilakukan operator
        PeranPengguna::firstOrCreate(
            ['nama_peran' => 'Supervisor'],
            [
                'deskripsi' => 'Supervisor unit yang dapat menyetujui pengangkutan limbah dan melihat status pengangkutan',
                'is_active' => true,
            ]
        );

        // Viewer - Hanya bisa melihat data (untuk keperluan audit/monitoring)
        PeranPengguna::firstOrCreate(
            ['nama_peran' => 'Viewer'],
            [
                'deskripsi' => 'Pengguna dengan akses hanya untuk melihat data tanpa dapat melakukan perubahan',
                'is_active' => true,
            ]
        );

        // Backward compatibility - Ensure Admin role exists as Administrator
        if (! PeranPengguna::where('nama_peran', 'Administrator')->exists()) {
            PeranPengguna::where('nama_peran', 'Admin')
                ->update([
                    'nama_peran' => 'Administrator',
                    'deskripsi' => 'Administrator unit dengan akses penuh ke semua menu kecuali master data unit lain',
                    'is_active' => true,
                ]);
        }
    }
}
