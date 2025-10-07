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
                'telepon_unit' => '0211234567',
                'keterangan' => 'Unit utama untuk koordinasi pusat.',
                'status_aktif' => true,
            ]
        );

        UnitPembangkit::firstOrCreate(
            ['nama_unit' => 'Unit Pembangkit Surabaya'],
            [
                'alamat_unit' => 'Jl. Pemuda No. 456, Surabaya',
                'kota' => 'Surabaya',
                'kode_pos' => '60271',
                'telepon_unit' => '0317654321',
                'keterangan' => 'Melayani wilayah Jawa Timur.',
                'status_aktif' => true,
            ]
        );

        UnitPembangkit::firstOrCreate(
            ['nama_unit' => 'Unit Pembangkit Medan'],
            [
                'alamat_unit' => 'Jl. Gatot Subroto No. 789, Medan',
                'kota' => 'Medan',
                'kode_pos' => '20112',
                'telepon_unit' => '0619876543',
                'keterangan' => 'Fokus operasional Sumatera.',
                'status_aktif' => true,
            ]
        );
    }
}
