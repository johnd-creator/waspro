<?php

namespace Database\Seeders;

use App\Models\JenisLimbah;
use App\Models\KarakteristikLimbah;
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
                'karakteristik' => 'Bersifat Infeksius',
                'deskripsi_limbah' => 'Limbah medis dari kegiatan perawatan pasien yang mengandung mikroorganisme berbahaya.',
            ],
            [
                'kode_limbah' => 'A102',
                'nama_limbah' => 'Limbah Kimia Beracun',
                'kemasan' => 'Drum Plastik',
                'jumlah_ton_per_tahun' => 12.3,
                'waktu_penyimpanan_hari' => 90,
                'karakteristik' => 'Beracun',
                'deskripsi_limbah' => 'Sisa bahan kimia industri dengan kandungan toksik tinggi.',
            ],
            [
                'kode_limbah' => 'A103',
                'nama_limbah' => 'Limbah Farmasi',
                'kemasan' => 'Kotak Karton',
                'jumlah_ton_per_tahun' => 3.2,
                'waktu_penyimpanan_hari' => 60,
                'karakteristik' => 'Beracun',
                'deskripsi_limbah' => 'Sisa produksi obat kadaluwarsa dan bahan aktif farmasi.',
            ],
            [
                'kode_limbah' => 'A104',
                'nama_limbah' => 'Limbah Minyak dan Oli Bekas',
                'kemasan' => 'Drum Logam',
                'jumlah_ton_per_tahun' => 25.8,
                'waktu_penyimpanan_hari' => 180,
                'karakteristik' => 'Mudah Terbakar',
                'deskripsi_limbah' => 'Minyak pelumas dan oli bekas dari perawatan mesin.',
            ],
            [
                'kode_limbah' => 'A105',
                'nama_limbah' => 'Limbah Elektronik',
                'kemasan' => 'Palet Kayu',
                'jumlah_ton_per_tahun' => 8.7,
                'waktu_penyimpanan_hari' => 120,
                'karakteristik' => 'Korosif',
                'deskripsi_limbah' => 'Komponen elektronik usang yang mengandung logam berat.',
            ],
        ];

        $karakteristikMap = KarakteristikLimbah::pluck('karakteristik_id', 'nama_karakteristik');

        foreach ($jenisLimbah as $item) {
            $karakteristikNama = $item['karakteristik'];

            if (! isset($karakteristikMap[$karakteristikNama])) {
                $karakteristik = KarakteristikLimbah::firstOrCreate(['nama_karakteristik' => $karakteristikNama]);
                $karakteristikMap[$karakteristikNama] = $karakteristik->karakteristik_id;
            }

            $payload = [
                'nama_limbah' => $item['nama_limbah'],
                'kemasan' => $item['kemasan'],
                'jumlah_ton_per_tahun' => $item['jumlah_ton_per_tahun'],
                'waktu_penyimpanan_hari' => $item['waktu_penyimpanan_hari'],
                'batas_penyimpanan_hari' => $item['waktu_penyimpanan_hari'],
                'karakteristik_id' => $karakteristikMap[$karakteristikNama],
                'deskripsi_limbah' => $item['deskripsi_limbah'] ?? null,
                'status_aktif' => $item['status_aktif'] ?? true,
            ];

            JenisLimbah::updateOrCreate(
                ['kode_limbah' => $item['kode_limbah']],
                $payload
            );
        }
    }
}
