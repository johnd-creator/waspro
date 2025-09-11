<?php

namespace Database\Seeders;

use App\Models\PerusahaanPenghasil;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerusahaanPenghasilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perusahaan = [
            ['nama_perusahaan' => 'PT. Kimia Farma Tbk', 'alamat_perusahaan' => 'Jl. Veteran No. 9, Jakarta Pusat', 'jenis_perusahaan' => 'Industri'],
            ['nama_perusahaan' => 'PT. Indocement Tunggal Prakarsa Tbk', 'alamat_perusahaan' => 'Jl. Jend. Sudirman Kav. 70-71, Jakarta', 'jenis_perusahaan' => 'Industri'],
            ['nama_perusahaan' => 'PT. Krakatau Steel Tbk', 'alamat_perusahaan' => 'Jl. Industri No. 5, Cilegon, Banten', 'jenis_perusahaan' => 'Industri'],
            ['nama_perusahaan' => 'PT. Pertamina (Persero)', 'alamat_perusahaan' => 'Jl. Medan Merdeka Timur 1A, Jakarta', 'jenis_perusahaan' => 'Industri'],
            ['nama_perusahaan' => 'PT. Pupuk Indonesia Holding Company', 'alamat_perusahaan' => 'Jl. TB Simatupang Kav. 1, Jakarta', 'jenis_perusahaan' => 'Industri'],
            ['nama_perusahaan' => 'PT. Semen Indonesia Tbk', 'alamat_perusahaan' => 'Jl. Veteran No. 53, Gresik, Jawa Timur', 'jenis_perusahaan' => 'Industri'],
            ['nama_perusahaan' => 'PT. Chandra Asri Petrochemical Tbk', 'alamat_perusahaan' => 'Jl. Raya Anyer Km. 123, Cilegon, Banten', 'jenis_perusahaan' => 'Industri'],
            ['nama_perusahaan' => 'PT. Unilever Indonesia Tbk', 'alamat_perusahaan' => 'Jl. BSD Boulevard Barat, Tangerang', 'jenis_perusahaan' => 'Industri'],
            ['nama_perusahaan' => 'PT. Astra International Tbk', 'alamat_perusahaan' => 'Jl. Gaya Motor Raya No. 8, Jakarta', 'jenis_perusahaan' => 'Industri'],
            ['nama_perusahaan' => 'PT. Freeport Indonesia', 'alamat_perusahaan' => 'Jl. Mega Kuningan Lot 8.1, Jakarta', 'jenis_perusahaan' => 'Industri'],
            ['nama_perusahaan' => 'RS. Cipto Mangunkusumo', 'alamat_perusahaan' => 'Jl. Diponegoro No. 71, Jakarta Pusat', 'jenis_perusahaan' => 'Rumah Sakit'],
            ['nama_perusahaan' => 'RS. Fatmawati', 'alamat_perusahaan' => 'Jl. RS Fatmawati Raya, Jakarta Selatan', 'jenis_perusahaan' => 'Rumah Sakit'],
            ['nama_perusahaan' => 'Laboratorium Klinik Prodia', 'alamat_perusahaan' => 'Jl. Kramat Raya No. 150, Jakarta Pusat', 'jenis_perusahaan' => 'Laboratorium'],
            ['nama_perusahaan' => 'PT. Kalbe Farma Tbk', 'alamat_perusahaan' => 'Jl. Let. Jend. Suprapto Kav. 4, Jakarta', 'jenis_perusahaan' => 'Industri'],
            ['nama_perusahaan' => 'PT. Sido Muncul Tbk', 'alamat_perusahaan' => 'Jl. Kaligawe Km. 4, Semarang, Jawa Tengah', 'jenis_perusahaan' => 'Industri'],
        ];

        foreach ($perusahaan as $item) {
            PerusahaanPenghasil::firstOrCreate(
                ['nama_perusahaan' => $item['nama_perusahaan']],
                $item
            );
        }
    }
}