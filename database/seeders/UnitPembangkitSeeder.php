<?php

namespace Database\Seeders;

use App\Models\UnitPembangkit;
use Illuminate\Database\Seeder;

class UnitPembangkitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UnitPembangkit::firstOrCreate(
            ['nama_unit' => 'Unit Pembangkit Pusat'],
            [
                'alamat_unit' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'kota' => 'Jakarta',
                'kode_pos' => '10220',
            ]
        );

        UnitPembangkit::firstOrCreate(
            ['nama_unit' => 'Unit Pembangkit Surabaya'],
            [
                'alamat_unit' => 'Jl. Pemuda No. 456, Surabaya',
                'kota' => 'Surabaya',
                'kode_pos' => '60271',
            ]
        );

        UnitPembangkit::firstOrCreate(
            ['nama_unit' => 'Unit Pembangkit Medan'],
            [
                'alamat_unit' => 'Jl. Gatot Subroto No. 789, Medan',
                'kota' => 'Medan',
                'kode_pos' => '20112',
            ]
        );
    }
}
