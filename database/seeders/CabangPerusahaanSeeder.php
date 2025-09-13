<?php

namespace Database\Seeders;

use App\Models\CabangPerusahaan;
use Illuminate\Database\Seeder;

class CabangPerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CabangPerusahaan::firstOrCreate(
            ['nama_cabang' => 'Cabang Pusat'],
            [
                'alamat_cabang' => 'Jl. Raya Utama No. 1',
                'kota' => 'Jakarta',
                'kode_pos' => '10110',
            ]
        );

        CabangPerusahaan::firstOrCreate(
            ['nama_cabang' => 'Cabang Surabaya'],
            [
                'alamat_cabang' => 'Jl. Pemuda No. 45',
                'kota' => 'Surabaya',
                'kode_pos' => '60271',
            ]
        );

        CabangPerusahaan::firstOrCreate(
            ['nama_cabang' => 'Cabang Bandung'],
            [
                'alamat_cabang' => 'Jl. Asia Afrika No. 123',
                'kota' => 'Bandung',
                'kode_pos' => '40112',
            ]
        );
    }
}
