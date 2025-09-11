<?php

namespace Database\Seeders;

use App\Models\JenisLimbah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JenisLimbahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jenisLimbah = [
            [
                'kode_limbah' => 'A101',
                'nama_limbah' => 'Limbah Medis Infeksius',
                'kemasan' => 'Kantong Plastik Kuning',
                'jumlah_ton_per_tahun' => 5.5,
                'waktu_penyimpanan_hari' => 30,
                'karakteristik_id' => 7, // Bersifat Infeksius
                'kategori_id' => 8, // Kegiatan Rumah Sakit
            ],
            [
                'kode_limbah' => 'A102',
                'nama_limbah' => 'Limbah Kimia Beracun',
                'kemasan' => 'Drum Plastik',
                'jumlah_ton_per_tahun' => 12.3,
                'waktu_penyimpanan_hari' => 90,
                'karakteristik_id' => 4, // Beracun
                'kategori_id' => 1, // Kegiatan Industri Kimia
            ],
            [
                'kode_limbah' => 'A103',
                'nama_limbah' => 'Limbah Farmasi',
                'kemasan' => 'Kotak Karton',
                'jumlah_ton_per_tahun' => 3.2,
                'waktu_penyimpanan_hari' => 60,
                'karakteristik_id' => 4, // Beracun
                'kategori_id' => 2, // Kegiatan Industri Farmasi
            ],
            [
                'kode_limbah' => 'A104',
                'nama_limbah' => 'Limbah Minyak dan Oli Bekas',
                'kemasan' => 'Drum Logam',
                'jumlah_ton_per_tahun' => 25.8,
                'waktu_penyimpanan_hari' => 180,
                'karakteristik_id' => 3, // Mudah Terbakar
                'kategori_id' => 6, // Kegiatan Industri Otomotif
            ],
            [
                'kode_limbah' => 'A105',
                'nama_limbah' => 'Limbah Elektronik',
                'kemasan' => 'Palet Kayu',
                'jumlah_ton_per_tahun' => 8.7,
                'waktu_penyimpanan_hari' => 120,
                'karakteristik_id' => 6, // Korosif
                'kategori_id' => 5, // Kegiatan Industri Elektronik
            ],
        ];

        foreach ($jenisLimbah as $item) {
            JenisLimbah::firstOrCreate(
                ['kode_limbah' => $item['kode_limbah']],
                $item
            );
        }
    }
}