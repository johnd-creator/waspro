<?php

namespace App\Services;

use App\Models\PerusahaanPenghasil;
use App\Models\LogPenyimpananLimbah;
use Illuminate\Support\Facades\DB;

class PerusahaanPenghasilService
{
    public function createPerusahaan(array $data): PerusahaanPenghasil
    {
        return PerusahaanPenghasil::create($data);
    }

    public function updatePerusahaan(PerusahaanPenghasil $perusahaan, array $data): PerusahaanPenghasil
    {
        return $perusahaan->update($data);
    }

    public function canDeletePerusahaan(PerusahaanPenghasil $perusahaan): array
    {
        $logCount = $perusahaan->logPenyimpanan()->count();

        if ($logCount > 0) {
            return [
                'can_delete' => false,
                'error' => 'Perusahaan penghasil tidak dapat dihapus karena masih digunakan dalam log penyimpanan.',
            ];
        }

        return [
            'can_delete' => true,
        'error' => null,
        ];
    }

    public function deletePerusahaan(PerusahaanPenghasil $perusahaan): bool
    {
        $canDelete = $this->canDeletePerusahaan($perusahaan);

        if (!$canDelete['can_delete']) {
            return false;
        }

        return DB::transaction(function () use ($perusahaan) {
            $perusahaan->logPenyimpanan()->update(['perusahaan_id' => null]);
            $perusahaan->delete();
            
            return true;
        });
    }

    public function getPerusahaanWithStats(?int $limit = null)
    {
        $query = PerusahaanPenghasil::withCount('logPenyimpanan')
            ->orderBy('nama_perusahaan');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function getPerusahaanByWasteVolume(int $limit = 10): array
    {
        return PerusahaanPenghasil::withCount('logPenyimpanan as total_logs')
            ->having('total_logs', '>', 0)
            ->orderByDesc('total_logs')
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function searchPerusahaan(string $keyword, int $perPage = 10)
    {
        return PerusahaanPenghasil::where('nama_perusahaan', 'LIKE', '%' . $keyword . '%')
            ->orWhere('jenis_perusahaan', 'LIKE', '%' . $keyword . '%')
            ->orWhere('kota', 'LIKE', '%' . $keyword . '%')
            ->orderBy('nama_perusahaan')
            ->paginate($perPage);
    }
}
