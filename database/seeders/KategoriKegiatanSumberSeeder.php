<?php

namespace Database\Seeders;

use App\Models\KategoriKegiatanSumber;
use Illuminate\Database\Seeder;

class KategoriKegiatanSumberSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        echo 'Creating fake Kategori data for testing...'.PHP_EOL;

        $kategori = [
            ['nama_kategori' => 'Kegiatan Industri Kimia'],
            ['nama_kategori' => 'Kegiatan Industri Farmasi'],
            ['nama_kategori' => 'kegiatanan Industri Tekstil'],
            ['nama_kategori' => 'kegiatanan Industri Logam'],
            ['nama_kategori' => 'kegiatanan Industri Elektronik'],
            ['nama_kategori' => 'kegiatanan Industri Otomotif'],
            ['nama_kategori' => 'kegiatanan Industri Makanan dan Minuman'],
            ['nama_kategori' => 'kegiatanan Rumah Sakit'],
            ['nama_kategori' => 'kegiatanan Laboratorium'],
            ['nama_kategori' => 'kegiatanan Pertambangan'],
            ['nama_kategori' => 'kegiatanan Migas'],
        ];

        $count = 0;
        foreach ($kategori as $item) {
            KategoriKegiatanSumber::firstOrCreate(
                ['nama_kategori' => $item['nama_kategori']]
            );
            $count++;
        }

        echo "✓ Created {$count} fake kategori records.".PHP_EOL;
        echo "Seeding completed! Created {$count} kategori kegiatan sumber records.".PHP_EOL;
    }
}
