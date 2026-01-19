<?php

use App\Models\LogPenyimpananLimbah;
use App\Models\JenisLimbah;
use Carbon\Carbon;

echo "🚀 Memulai pembuatan 50 sample log tambahan...\n\n";

$jenisLimbahs = JenisLimbah::all();
$perusahaanIds = [2, 7, 9, 10, 13];
$unitIds = [1, 2, 3];
$userIds = [4, 5];
$statusLogs = ['Tersimpan', 'Tersimpan', 'Tersimpan', 'Tersimpan', 'Tersimpan', 'Diangkut'];

$uraianPekerjaan = [
    'Pengumpulan limbah harian',
    'Penyimpanan limbah mingguan',
    'Penerimaan limbah dari gudang',
    'Pengumpulan limbah dari klien',
    'Penyimpanan limbah batch bulanan',
    'Pengumpulan limbah dari proses produksi',
    'Penerimaan limbah dari area kerja',
    'Pengumpulan limbah dari maintenance',
];

$detailSumber = [
    'Laboratorium - sampah uji dan bahan kimia',
    'Gudang - packaging bekas dan kardus',
    'Produksi - limbah dari proses manufaktur',
    'Kantin - sisa makanan dan plastik',
    'Kantor - kertas bekas dan toner printer',
    'Workshop - oli bekas dan suku cadang',
    'Rumah sakit - limbah medis dan B3',
];

$createdCount = 0;
$failedCount = 0;

for ($i = 1; $i <= 50; $i++) {
    try {
        $jenisLimbah = $jenisLimbahs->random();
        $perusahaanId = $perusahaanIds[array_rand($perusahaanIds)];
        $unitId = $unitIds[array_rand($unitIds)];
        $userId = $userIds[array_rand($userIds)];
        $statusLog = $statusLogs[array_rand($statusLogs)];

        // Random tanggal dalam 3 bulan terakhir
        $randomDays = rand(0, 90);
        $tanggalMasuk = Carbon::now()->subDays($randomDays);

        // Random jumlah antara 50 dan 1000
        $jumlahLimbah = round(rand(50, 1000) + (rand(0, 99) / 100), 2);

        $expiryDate = $tanggalMasuk->copy()->addDays($jenisLimbah->waktu_penyimpanan_hari);

        // Jika status Diangkut, set tanggal pengangkutan
        $tanggalPengangkutan = null;
        $jumlahDiangkut = 0;
        if ($statusLog === 'Diangkut') {
            $tanggalPengangkutan = $tanggalMasuk->copy()->addDays(rand(5, 30));
            $jumlahDiangkut = $jumlahLimbah;
        }

        $log = LogPenyimpananLimbah::create([
            'kode_limbah' => $jenisLimbah->kode_limbah,
            'perusahaan_id' => $perusahaanId,
            'unit_id' => $unitId,
            'user_id' => $userId,
            'tanggal_limbah_masuk' => $tanggalMasuk,
            'jumlah_limbah_masuk' => $jumlahLimbah,
            'detail_sumber_limbah' => $detailSumber[array_rand($detailSumber)],
            'uraian_pekerjaan' => $uraianPekerjaan[array_rand($uraianPekerjaan)],
            'status_log' => $statusLog,
            'tanggal_pengangkutan' => $tanggalPengangkutan,
            'jumlah_diangkut' => $jumlahDiangkut,
            'tanggal_kadaluarsa' => $expiryDate,
            'maksimal_penyimpanan_tanggal' => $expiryDate,
        ]);

        // Update expiry status untuk log yang tersimpan
        if ($statusLog === 'Tersimpan') {
            $log->updateExpiryStatus();
        }

        $createdCount++;
        if ($createdCount % 10 === 0) {
            echo "✅ Created {$createdCount} logs...\n";
        }
    } catch (\Exception $e) {
        $failedCount++;
        echo "❌ Failed to create log {$i}: " . $e->getMessage() . "\n";
    }
}

echo "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "📊 SUMMARY:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "Total created: {$createdCount} logs\n";
echo "Total failed: {$failedCount} logs\n";
echo "\n";

echo "📈 Total Statistics:\n";
$totalLogs = LogPenyimpananLimbah::count();
$tersimpan = LogPenyimpananLimbah::where('status_log', 'Tersimpan')->count();
$diangkut = LogPenyimpananLimbah::where('status_log', 'Diangkut')->count();
$kadaluarsa = LogPenyimpananLimbah::where('status_log', 'Kadaluarsa')->count();
$safe = LogPenyimpananLimbah::where('expiry_status', 'Safe')->count();
$warning = LogPenyimpananLimbah::where('expiry_status', 'Warning')->count();
$critical = LogPenyimpananLimbah::where('expiry_status', 'Critical')->count();
$expired = LogPenyimpananLimbah::where('expiry_status', 'Expired')->count();

echo "Total logs in database: {$totalLogs}\n";
echo "  ├─ Tersimpan: {$tersimpan}\n";
echo "  ├─ Diangkut: {$diangkut}\n";
echo "  └─ Kadaluarsa: {$kadaluarsa}\n";
echo "\nExpiry Status:\n";
echo "  ├─ Safe: {$safe}\n";
echo "  ├─ Warning: {$warning}\n";
echo "  ├─ Critical: {$critical}\n";
echo "  └─ Expired: {$expired}\n";
echo "\n";

echo "✅ Additional sample data creation completed!\n";
