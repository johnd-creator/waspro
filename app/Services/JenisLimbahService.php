<?php

namespace App\Services;

use App\Models\JenisLimbah;

class JenisLimbahService
{
    public function createJenisLimbah(array $data): JenisLimbah
    {
        $data['batas_penyimpanan_hari'] = $data['waktu_penyimpanan_hari'] ?? null;
        
        return JenisLimbah::create($data);
    }

    public function updateJenisLimbah(JenisLimbah $jenisLimbah, array $data): bool
    {
        if (isset($data['batas_penyimpanan_hari']) || isset($data['waktu_penyimpanan_hari'])) {
            $data['batas_penyimpanan_hari'] = $data['waktu_penyimpanan_hari'] ?? $data['batas_penyimpanan_hari'] ?? $jenisLimbah->waktu_penyimpanan_hari;
        }

        return $jenisLimbah->update($data);
    }

    public function checkExpiryForLog(string $kodeLimbah, string $tanggalMasuk): ?\Carbon\Carbon
    {
        $jenisLimbah = JenisLimbah::where('kode_limbah', $kodeLimbah)->first();
        
        if (!$jenisLimbah) {
            return null;
        }

        return \Carbon\Carbon::parse($tanggalMasuk)->addDays($jenisLimbah->waktu_penyimpanan_hari);
    }

    public function getActiveJenisLimbah()
    {
        return JenisLimbah::where('status_aktif', true)
            ->orderBy('nama_limbah')
            ->get();
    }

    public function getJenisLimbahWithRelations()
    {
        return JenisLimbah::with(['karakteristik', 'kategoriKegiatanSumber'])
            ->orderBy('kode_limbah')
            ->get();
    }
}
