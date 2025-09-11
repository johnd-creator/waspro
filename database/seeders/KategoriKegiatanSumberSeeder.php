<?php

namespace Database\Seeders;

use App\Models\KategoriKegiatanSumber;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriKegiatanSumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Kegiatan Industri Kimia'],
            ['nama_kategori' => 'Kegiatan Industri Farmasi'],
            ['nama_kategori' => 'Kegiatan Industri Tekstil'],
            ['nama_kategori' => 'Kegiatan Industri Logam'],
            ['nama_kategori' => 'Kegiatan Industri Elektronik'],
            ['nama_kategori' => 'Kegiatan Industri Otomotif'],
            ['nama_kategori' => 'Kegiatan Industri Makanan dan Minuman'],
            ['nama_kategori' => 'Kegiatan Rumah Sakit'],
            ['nama_kategori' => 'Kegiatan Laboratorium'],
            ['nama_kategori' => 'Kegiatan Pertambangan'],
            ['nama_kategori' => 'Kegiatan Migas'],
            ['nama_kategori' => 'Kegiatan Lainnya'],
        ];

        foreach ($kategori as $item) {
            KategoriKegiatanSumber::firstOrCreate(
                ['nama_kategori' => $item['nama_kategori']]
            );
        }
    }
}