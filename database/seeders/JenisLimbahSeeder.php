<?php

namespace Database\Seeders;

use App\Models\JenisLimbah;
use Illuminate\Database\Seeder;

class JenisLimbahSeeder extends Seeder
{
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
                'status_aktif' => true,
                'biaya_pengangkutan_per_kg' => 15000.00,
                'mulai_berlaku' => '2026-01-01',
                'akhir_berlaku' => null,
            ],
            [
                'kode_limbah' => 'A102',
                'nama_limbah' => 'Limbah Kimia Beracun',
                'kemasan' => 'Drum Plastik',
                'jumlah_ton_per_tahun' => 12.3,
                'waktu_penyimpanan_hari' => 90,
                'karakteristik' => 'Kimia Beracun',
                'deskripsi_limbah' => 'Limbah kimia beracun dari kegiatan industri dan laboratorium.',
                'status_aktif' => true,
                'biaya_pengangkutan_per_kg' => 25000.00,
                'mulai_berlaku' => '2026-01-01',
                'akhir_berlaku' => null,
            ],
            [
                'kode_limbah' => 'A103',
                'nama_limbah' => 'Limbah Farmasi',
                'kemasan' => 'Kotak Karton',
                'jumlah_ton_per_tahun' => 3.2,
                'waktu_penyimpanan_hari' => 60,
                'karakteristik' => 'Organik',
                'deskripsi_limbah' => 'Sisa produksi obat kadaluwarsa dan bahan aktif farmasi.',
                'status_aktif' => true,
                'biaya_pengangkutan_per_kg' => 3200.00,
                'mulai_berlaku' => '2026-01-01',
                'akhir_berlaku' => null,
            ],
            [
                'kode_limbah' => 'A104',
                'nama_limbah' => 'Limbah Minyak dan Oli Bekas',
                'kemasan' => 'Drum Logam',
                'jumlah_ton_per_tahun' => 25.8,
                'waktu_penyimpanan_hari' => 180,
                'karakteristik' => 'Minyak',
                'deskripsi_limbah' => 'Minyak bekas dan oli bekas dari perawatan mesin dan kendaraan.',
                'status_aktif' => true,
                'biaya_pengangkutan_per_kg' => 20000.00,
                'mulai_berlaku' => '2026-01-01',
                'akhir_berlaku' => null,
            ],
            [
                'kode_limbah' => 'A105',
                'nama_limbah' => 'Limbah Elektronik',
                'kemasan' => 'Palet Kayu',
                'jumlah_ton_per_tahun' => 8.7,
                'waktu_penyimpanan_hari' => 120,
                'karakteristik' => 'Elektronik',
                'deskripsi_limbah' => 'Komponen elektronik usang yang mengandung logam berbahaya.',
                'status_aktif' => true,
                'biaya_pengangkutan_per_kg' => 30000.00,
                'mulai_berlaku' => '2026-01-01',
                'akhir_berlaku' => null,
            ],
        ];

        foreach ($jenisLimbah as $item) {
            $payload = [
                'nama_limbah' => $item['nama_limbah'],
                'kemasan' => $item['kemasan'],
                'jumlah_ton_per_tahun' => $item['jumlah_ton_per_tahun'],
                'waktu_penyimpanan_hari' => $item['waktu_penyimpanan_hari'],
                'karakteristik_id' => 1,
                'deskripsi_limbah' => $item['deskripsi_limbah'],
                'status_aktif' => $item['status_aktif'] ?? true,
                'biaya_pengangkutan_per_kg' => $item['biaya_pengangkutan_per_kg'],
                'mulai_berlaku' => $item['mulai_berlaku'],
                'akhir_berlaku' => $item['akhir_berlaku'],
            ];

            JenisLimbah::updateOrCreate(
                ['kode_limbah' => $item['kode_limbah']],
                $payload
            );
        }
    }
}
