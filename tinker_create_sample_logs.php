<?php

use App\Models\LogPenyimpananLimbah;
use Carbon\Carbon;

echo "🚀 Memulai pembuatan sample data log penyimpanan...\n\n";

$sampleData = [
    [
        'kode_limbah' => 'A101',
        'perusahaan_id' => 13,
        'unit_id' => 1,
        'user_id' => 4,
        'tanggal_limbah_masuk' => '2026-01-15',
        'jumlah_limbah_masuk' => 150.50,
        'detail_sumber_limbah' => 'Laboratorium Prodia - sampah jarum suntik dan botol obat',
        'uraian_pekerjaan' => 'Pengumpulan limbah medis harian',
        'status_log' => 'Tersimpan',
    ],
    [
        'kode_limbah' => 'A102',
        'perusahaan_id' => 9,
        'unit_id' => 1,
        'user_id' => 4,
        'tanggal_limbah_masuk' => '2026-01-16',
        'jumlah_limbah_masuk' => 75.25,
        'detail_sumber_limbah' => 'PT. Astra - limbah kimia beracun dari proses produksi',
        'uraian_pekerjaan' => 'Penerimaan limbah kimia dari gudang B',
        'status_log' => 'Tersimpan',
    ],
    [
        'kode_limbah' => 'A103',
        'perusahaan_id' => 7,
        'unit_id' => 2,
        'user_id' => 3,
        'tanggal_limbah_masuk' => '2026-01-14',
        'jumlah_limbah_masuk' => 200.00,
        'detail_sumber_limbah' => 'PT. Chandra Asri - limbah farmasi kadaluarsa',
        'uraian_pekerjaan' => 'Penyimpanan limbah farmasi batch januari',
        'status_log' => 'Diangkut',
        'tanggal_pengangkutan' => '2026-01-18',
        'jumlah_diangkut' => 200.00,
    ],
    [
        'kode_limbah' => 'A104',
        'perusahaan_id' => 10,
        'unit_id' => 3,
        'user_id' => 5,
        'tanggal_limbah_masuk' => '2026-01-17',
        'jumlah_limbah_masuk' => 500.00,
        'detail_sumber_limbah' => 'PT. Freeport - minyak dan oli bekas dari mesin berat',
        'uraian_pekerjaan' => 'Pengumpulan oli bekas minggu ke-3',
        'status_log' => 'Tersimpan',
    ],
    [
        'kode_limbah' => 'A105',
        'perusahaan_id' => 2,
        'unit_id' => 1,
        'user_id' => 4,
        'tanggal_limbah_masuk' => '2026-01-10',
        'jumlah_limbah_masuk' => 350.75,
        'detail_sumber_limbah' => 'PT. Indocement - limbah elektronik dari kantor pusat',
        'uraian_pekerjaan' => 'Penyimpanan komputer dan printer bekas',
        'status_log' => 'Tersimpan',
    ],
    [
        'kode_limbah' => 'A101',
        'perusahaan_id' => 9,
        'unit_id' => 1,
        'user_id' => 4,
        'tanggal_limbah_masuk' => '2026-01-12',
        'jumlah_limbah_masuk' => 100.00,
        'detail_sumber_limbah' => 'PT. Astra - limbah medis dari klinik perusahaan',
        'uraian_pekerjaan' => 'Penerimaan limbah medis klinik Astra',
        'status_log' => 'Diangkut',
        'tanggal_pengangkutan' => '2026-01-19',
        'jumlah_diangkut' => 100.00,
    ],
    [
        'kode_limbah' => 'A102',
        'perusahaan_id' => 13,
        'unit_id' => 2,
        'user_id' => 3,
        'tanggal_limbah_masuk' => '2026-01-18',
        'jumlah_limbah_masuk' => 85.50,
        'detail_sumber_limbah' => 'Laboratorium Prodia - bahan kimia beracun lab',
        'uraian_pekerjaan' => 'Pengumpulan limbah kimia lab uji',
        'status_log' => 'Tersimpan',
    ],
    [
        'kode_limbah' => 'A103',
        'perusahaan_id' => 10,
        'unit_id' => 3,
        'user_id' => 5,
        'tanggal_limbah_masuk' => '2026-01-05',
        'jumlah_limbah_masuk' => 180.25,
        'detail_sumber_limbah' => 'PT. Freeport - obat-obatan kadaluarsa',
        'uraian_pekerjaan' => 'Penyimpanan farmasi batch desember',
        'status_log' => 'Kadaluarsa',
    ],
    [
        'kode_limbah' => 'A104',
        'perusahaan_id' => 7,
        'unit_id' => 2,
        'user_id' => 3,
        'tanggal_limbah_masuk' => '2026-01-13',
        'jumlah_limbah_masuk' => 250.00,
        'detail_sumber_limbah' => 'PT. Chandra Asri - oli bekas generator',
        'uraian_pekerjaan' => 'Pengumpulan oli generator utama',
        'status_log' => 'Tersimpan',
    ],
    [
        'kode_limbah' => 'A105',
        'perusahaan_id' => 2,
        'unit_id' => 1,
        'user_id' => 4,
        'tanggal_limbah_masuk' => '2026-01-08',
        'jumlah_limbah_masuk' => 175.00,
        'detail_sumber_limbah' => 'PT. Indocement - limbah elektronik lama',
        'uraian_pekerjaan' => 'Penyimpanan TV dan monitor bekas',
        'status_log' => 'Tersimpan',
    ],
];

$createdCount = 0;
$failedCount = 0;

foreach ($sampleData as $data) {
    try {
        $jenisLimbah = \App\Models\JenisLimbah::find($data['kode_limbah']);
        if (!$jenisLimbah) {
            $failedCount++;
            echo "❌ Jenis limbah {$data['kode_limbah']} not found\n";
            continue;
        }

        $expiryDate = Carbon::parse($data['tanggal_limbah_masuk'])
            ->addDays($jenisLimbah->waktu_penyimpanan_hari);

        $data['tanggal_kadaluarsa'] = $expiryDate;
        $data['maksimal_penyimpanan_tanggal'] = $expiryDate;

        $log = LogPenyimpananLimbah::create($data);

        if ($data['status_log'] === 'Tersimpan') {
            $log->updateExpiryStatus();
        }

        $createdCount++;
        echo "✅ Log #{$log->log_id} created: {$log->kode_identitas}\n";
    } catch (\Exception $e) {
        $failedCount++;
        echo "❌ Failed to create log: " . $e->getMessage() . "\n";
    }
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 SUMMARY:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Total created: {$createdCount} logs\n";
echo "Total failed: {$failedCount} logs\n";
echo "\n";

echo "📈 Current Statistics:\n";
$totalLogs = LogPenyimpananLimbah::count();
$tersimpan = LogPenyimpananLimbah::where('status_log', 'Tersimpan')->count();
$diangkut = LogPenyimpananLimbah::where('status_log', 'Diangkut')->count();
$kadaluarsa = LogPenyimpananLimbah::where('status_log', 'Kadaluarsa')->count();

echo "Total logs in database: {$totalLogs}\n";
echo "  ├─ Tersimpan: {$tersimpan}\n";
echo "  ├─ Diangkut: {$diangkut}\n";
echo "  └─ Kadaluarsa: {$kadaluarsa}\n";
echo "\n";

echo "✅ Sample data creation completed!\n";
