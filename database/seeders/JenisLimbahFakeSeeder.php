<?php

namespace Database\Seeders;

use App\Models\JenisLimbah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisLimbahFakeSeeder extends Seeder
{
    /**
     * Run database seeds.
     */
    public function run(): void
    {
        echo 'Creating fake Jenis Limbah data for testing...'.PHP_EOL;

        // Clean existing fake data for clean testing
        DB::table('jenis_limbah')->where('kode_limbah', 'LIKE', 'TEST%')->delete();

        // Clean existing kategori_kegiatan_sumber data
        DB::table('kategori_kegiatan_sumber')->where('nama_kategori', 'LIKE', 'FAKE%')->delete();

        // Define unique fake data with realistic values
        $fakeData = [
            [
                'kode_limbah' => 'LMB001',
                'nama_limbah' => 'Limbah Medis Infeksius',
                'kemasan' => 'Kantong Plastik Kuning',
                'jumlah_ton_per_tahun' => 150.50,
                'waktu_penyimpanan_hari' => 90,
                'batas_penyimpanan_hari' => 180,
                'karakteristik_id' => 1,
                'deskripsi_limbah' => 'Limbah medis dari infeksius untuk uji coba',
                'status_aktif' => true,
                'biaya_pengangkutan_per_kg' => 75000,
                'mulai_berlaku' => '2024-01-01',
                'akhir_berlaku' => '2026-12-31',
                'keterangan_biaya' => 'Biaya standard untuk testing',
            ],
            [
                'kode_limbah' => 'LMB002',
                'nama_limbah' => 'Limbah Radioaktif',
                'kemasan' => 'IBC Tonic',
                'jumlah_ton_per_tahun' => 200.00,
                'waktu_penyimpanan_hari' => 120,
                'batas_penyimpanan_hari' => 365,
                'karakteristik_id' => 1,
                'kategori_id' => 1,
                'deskripsi_limbah' => 'Limbah radioaktif dari infeksius',
                'status_aktif' => true,
                'biaya_pengangkutan_per_kg' => 150000,
                'mulai_berlaku' => '2024-01-01',
                'akhir_berlaku' => '2026-12-31',
                'keterangan_biaya' => 'Biaya untuk tahun 2024',
            ],
            [
                'kode_limbah' => 'LMB003',
                'nama_limbah' => 'Limbah Patologi',
                'kemasan' => 'Drum Besi',
                'jumlah_ton_per_tahun' => 500.00,
                'waktu_penyimpanan_hari' => 60,
                'batas_penyimpanan_hari' => 90,
                'karakteristik_id' => 1,
                'kategori_id' => 1,
                'deskripsi_limbah' => 'Limbah patologi dari drumb besi untuk uji',
                'status_aktif' => true,
                'biaya_pengangkutan_per_kg' => 85000,
                'mulai_berlaku' => '2024-01-01',
                'akhir_berlaku' => '2026-12-31',
                'keterangan_biaya' => 'Biaya standar untuk tahun 2024',
            ],
            [
                'kode_limbah' => 'LMB004',
                'nama_limbah' => 'Limbah Kimia',
                'kemasan' => 'IBC Tonic',
                'jumlah_ton_per_tahun' => 300.00,
                'waktu_penyimpanan_hari' => 180,
                'batas_penyimpanan_hari' => 365,
                'karakteristik_id' => 2,
                'kategori_id' => 2,
                'deskripsi_limbah' => 'Limbah kimia industri dari kimia',
                'status_aktif' => true,
                'biaya_pengangkutan_per_kg' => 100000,
                'mulai_berlaku' => '2025-01-01',
                'akhir_berlaku' => '2025-12-31',
                'keterangan_biaya' => 'Biaya standar untuk tahun 2025',
            ],
            [
                'kode_limbah' => 'LMB005',
                'nama_limbah' => 'Limbah Farmasi',
                'kemasan' => 'IBC',
                'jumlah_ton_per_tahun' => 100.00,
                'waktu_penyimpanan_hari' => 30,
                'batas_penyimpanan_hari' => 60,
                'karakteristik_id' => 3,
                'kategori_id' => 3,
                'deskripsi_limbah' => 'Limbah farmasi dari pertanian',
                'status_aktif' => false,
                'biaya_pengangkutan_per_kg' => 50000,
                'mulai_berlaku' => '2025-01-01',
                'akhir_berlaku' => '2026-12-31',
                'keterangan_biaya' => 'Biaya untuk limbah pertanian yang masih dipakai',
            ],
            [
                'kode_limbah' => 'LMB006',
                'nama_limbah' => 'Limbah Farmasi',
                'kemasan' => 'IBC',
                'jumlah_ton_per_tahun' => 100.00,
                'waktu_penyimpanan_hari' => 30,
                'batas_penyimpanan_hari' => 60,
                'karakteristik_id' => 3,
                'kategori_id' => 4,
                'deskripsi_limbah' => 'Limbah farmasi kedua',
                'status_aktif' => false,
                'biaya_pengangkutan_per_kg' => 65000,
                'mulai_berlaku' => '2025-01-01',
                'akhir_berlaku' => '2026-12-31',
                'keterangan_biaya' => 'Biaya untuk farmasi kedua',
            ],
            [
                'kode_limbah' => 'LMB007',
                'nama_limbah' => 'Limbah Tekstil',
                'kemasan' => 'IBC Tonic',
                'jumlah_ton_per_tahun' => 150.00,
                'waktu_penyimpanan_hari' => 45,
                'batas_penyimpanan_hari' => 90,
                'karakteristik_id' => 4,
                'kategori_id' => 5,
                'deskripsi_limbah' => 'Limbah teknis industri elektronik dari PCB',
                'status_aktif' => true,
                'biaya_pengangkutan_per_kg' => 85000,
                'mulai_berlaku' => '2024-01-01',
                'akhir_berlaku' => '2026-12-31',
                'keterangan_biaya' => 'Biaya standar untuk limbah teknis',
            ],
            [
                'kode_limbah' => 'LMB008',
                'nama_limbah' => 'Limbah Logam',
                'kemasan' => 'IBC',
                'jumlah_ton_per_tahun' => 200.00,
                'waktu_penyimpanan_hari' => 120,
                'batas_penyimpanan_hari' => 365,
                'karakteristik_id' => 5,
                'kategori_id' => 6,
                'deskripsi_limbah' => 'Limbah logam dari limbah logam industri kimia',
                'status_aktif' => true,
                'biaya_pengangkutan_per_kg' => 80000,
                'mulai_berlaku' => '2024-01-01',
                'akhir_berlaku' => '2026-12-31',
                'keterangan_biaya' => 'Biaya standar untuk limbah logam',
            ],
            [
                'kode_limbah' => 'LMB009',
                'nama_limbah' => 'Limbah Elektronik',
                'kemasan' => 'IBC',
                'jumlah_ton_per_tahun' => 250.00,
                'waktu_penyimpanan_hari' => 30,
                'batas_penyimpanan_hari' => 60,
                'karakteristik_id' => 6,
                'kategori_id' => 7,
                'deskripsi_limbah' => 'Limbah elektronik dari perangkat elektronik',
                'status_aktif' => true,
                'biaya_pengangkutan_per_kg' => 120000,
                'mulai_berlaku' => '2024-01-01',
                'akhir_berlaku' => '2026-12-31',
                'keterangan_biaya' => 'Biaya untuk limbah elektronik',
            ],
            [
                'kode_limbah' => 'LMB010',
                'nama_limbah' => 'Limbah Otomotif',
                'kemasan' => 'IBC',
                'jumlah_ton_per_tahun' => 100.00,
                'waktu_penyimpanan_hari' => 30,
                'batas_penyimpanan_hari' => 60,
                'karakteristik_id' => 7,
                'kategori_id' => 8,
                'deskripsi_limbah' => 'Limbah otomotif dari infeksius',
                'status_aktif' => true,
                'biaya_pengangkutan_per_kg' => 100000,
                'mulai_berlaku' => '2024-01-01',
                'akhir_berlaku' => '2026-12-31',
                'keterangan_biaya' => 'Biaya untuk limbah otomotif',
            ],
        ];

        $count = 0;
        foreach ($fakeData as $data) {
            JenisLimbah::create($data);
            $count++;
            echo "✓ Created: {$data['nama_limbah']} - {$data['kode_limbah']}".PHP_EOL;
        }

        echo "Seeding completed! Created {$count} fake jenis limbah records.".PHP_EOL;
    }
}
