<?php

namespace App\Services;

use App\Models\LogPenyimpananLimbah;
use App\Models\JenisLimbah;
use App\Models\PerusahaanPenghasil;
use Carbon\Carbon;

class LogPenyimpananService
{
    public function getFilteredLogs(array $filters, int $perPage = 15)
    {
        $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unit', 'penggunaSistem']);

        if (empty($filters['search_status'])) {
            $query->where('status_log', '!=', \App\Enums\LogStatus::Diangkut->value);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('kode_identitas', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('uraian_pekerjaan', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('status_log', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhereHas('jenisLimbah', function ($jl) use ($filters) {
                        $jl->where('nama_limbah', 'LIKE', '%' . $filters['search'] . '%')
                            ->orWhere('kode_limbah', 'LIKE', '%' . $filters['search'] . '%');
                    })
                    ->orWhereHas('perusahaanPenghasil', function ($pp) use ($filters) {
                        $pp->where('nama_perusahaan', 'LIKE', '%' . $filters['search'] . '%');
                    })
                    ->orWhereHas('penggunaSistem', function ($ps) use ($filters) {
                        $ps->where('nama_lengkap', 'LIKE', '%' . $filters['search'] . '%')
                            ->orWhere('email_address', 'LIKE', '%' . $filters['search'] . '%');
                    })
                    ->orWhereHas('unit', function ($u) use ($filters) {
                        $u->where('nama_unit', 'LIKE', '%' . $filters['search'] . '%');
                    });
            });
        }

        if (!empty($filters['search_unit_id'])) {
            $query->where('unit_id', $filters['search_unit_id']);
        }

        if (!empty($filters['search_status'])) {
            $query->where('status_log', $filters['search_status']);
        }

        return $query->orderBy('tanggal_limbah_masuk', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function calculateExpiryDate(string $kodeLimbah, string $tanggalMasuk): ?Carbon
    {
        $jenisLimbah = JenisLimbah::where('kode_limbah', $kodeLimbah)->first();

        if (!$jenisLimbah) {
            return null;
        }

        return Carbon::parse($tanggalMasuk)->addDays($jenisLimbah->waktu_penyimpanan_hari);
    }

    public function findOrCreatePerusahaan(string $namaPerusahaan): PerusahaanPenghasil
    {
        return PerusahaanPenghasil::firstOrCreate(
            ['nama_perusahaan' => $namaPerusahaan],
            [
                'jenis_perusahaan' => 'Lain-lain',
                'alamat_perusahaan' => '-',
                'status_aktif' => true,
            ]
        );
    }

    public function uploadDocument($file): array
    {
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $secureFilename = \Illuminate\Support\Str::slug($filename) . '_' . time() . '.' . $extension;

        $directory = 'documents/log_penyimpanan/' . now()->format('Y/m');
        $path = $file->storeAs($directory, $secureFilename, 'local');

        return [
            'path' => $path,
            'filename' => $secureFilename,
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
        ];
    }

    public function createLog(array $data, ?int $userId = null, ?int $unitId = null): LogPenyimpananLimbah
    {
        $expiryDate = $this->calculateExpiryDate($data['kode_limbah'], $data['tanggal_limbah_masuk']);

        $perusahaan = null;
        if (!empty($data['perusahaan_nama'])) {
            $perusahaan = $this->findOrCreatePerusahaan($data['perusahaan_nama']);
        }

        return LogPenyimpananLimbah::create([
            'unit_id' => $unitId ?? $data['unit_id'] ?? null,
            'user_id' => $userId ?? auth()->id(),
            'kode_identitas' => $this->generateKodeIdentitas($unitId ?? $data['unit_id'] ?? auth()->user()->unit_id),
            'tanggal_limbah_masuk' => $data['tanggal_limbah_masuk'],
            'detail_sumber_limbah' => $data['detail_sumber_limbah'],
            'uraian_pekerjaan' => $data['uraian_pekerjaan'] ?? null,
            'jumlah_limbah_masuk' => $data['jumlah_limbah_masuk'],
            'kode_limbah' => $data['kode_limbah'],
            'perusahaan_id' => $perusahaan?->perusahaan_id,
            'dokumen_path' => $data['dokumen_path'] ?? null,
            'tanggal_kadaluarsa' => $expiryDate,
            'maksimal_penyimpanan_tanggal' => $expiryDate,
            'status_log' => \App\Enums\LogStatus::Tersimpan,
        ]);
    }

    protected function generateKodeIdentitas(?int $unitId): string
    {
        $prefix = $unitId ? 'UNIT' . $unitId : 'LOG';
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(substr(md5(uniqid()), 0, 4));

        return $prefix . '-' . $timestamp . '-' . $random;
    }

    public function updateLog(LogPenyimpananLimbah $log, array $data): bool
    {
        if ($log->status_log === \App\Enums\LogStatus::Diangkut) {
            return false;
        }

        $expiryDate = null;
        if (isset($data['kode_limbah']) || isset($data['tanggal_limbah_masuk'])) {
            $kodeLimbah = $data['kode_limbah'] ?? $log->kode_limbah;
            $tanggalMasuk = $data['tanggal_limbah_masuk'] ?? $log->tanggal_limbah_masuk;
            $expiryDate = $this->calculateExpiryDate($kodeLimbah, $tanggalMasuk);
        }

        $perusahaanId = $log->perusahaan_id;
        if (!empty($data['perusahaan_nama'])) {
            $perusahaanId = $this->findOrCreatePerusahaan($data['perusahaan_nama'])->perusahaan_id;
        }

        $updateData = [];

        $fillableFields = [
            'unit_id',
            'tanggal_limbah_masuk',
            'detail_sumber_limbah',
            'uraian_pekerjaan',
            'jumlah_limbah_masuk',
            'kode_limbah',
            'dokumen_path',
        ];

        foreach ($fillableFields as $field) {
            if (array_key_exists($field, $data)) {
                $updateData[$field] = $data[$field];
            }
        }

        if ($perusahaanId !== $log->perusahaan_id) {
            $updateData['perusahaan_id'] = $perusahaanId;
        }

        if ($expiryDate) {
            $updateData['tanggal_kadaluarsa'] = $expiryDate;
            $updateData['maksimal_penyimpanan_tanggal'] = $expiryDate;
        }

        return $log->update($updateData);
    }

    public function getFilteredLogsForExport(array $filters, bool $defaultStatus = true)
    {
        $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit', 'penggunaSistem']);

        if ($defaultStatus && empty($filters['search_status'])) {
            $query->where('status_log', '!=', \App\Enums\LogStatus::Diangkut->value);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('kode_identitas', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('uraian_pekerjaan', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('status_log', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhereHas('jenisLimbah', function ($jl) use ($filters) {
                        $jl->where('nama_limbah', 'LIKE', '%' . $filters['search'] . '%')
                            ->orWhere('kode_limbah', 'LIKE', '%' . $filters['search'] . '%');
                    })
                    ->orWhereHas('perusahaanPenghasil', function ($pp) use ($filters) {
                        $pp->where('nama_perusahaan', 'LIKE', '%' . $filters['search'] . '%');
                    })
                    ->orWhereHas('penggunaSistem', function ($ps) use ($filters) {
                        $ps->where('nama_lengkap', 'LIKE', '%' . $filters['search'] . '%')
                            ->orWhere('email_address', 'LIKE', '%' . $filters['search'] . '%');
                    })
                    ->orWhereHas('unit', function ($u) use ($filters) {
                        $u->where('nama_unit', 'LIKE', '%' . $filters['search'] . '%');
                    });
            });
        }

        if (!empty($filters['search_jenis'])) {
            $query->whereHas('jenisLimbah', function ($q) use ($filters) {
                $q->where('nama_limbah', 'LIKE', '%' . $filters['search_jenis'] . '%')
                    ->orWhere('kode_limbah', 'LIKE', '%' . $filters['search_jenis'] . '%');
            });
        }

        if (!empty($filters['search_uraian_pekerjaan'])) {
            $query->where('uraian_pekerjaan', 'LIKE', '%' . $filters['search_uraian_pekerjaan'] . '%');
        }

        if (!empty($filters['search_perusahaan'])) {
            $query->whereHas('perusahaanPenghasil', function ($q) use ($filters) {
                $q->where('nama_perusahaan', 'LIKE', '%' . $filters['search_perusahaan'] . '%');
            });
        }

        if (!empty($filters['search_status'])) {
            $query->where('status_log', $filters['search_status']);
        }

        if (!empty($filters['search_tanggal'])) {
            $query->whereDate('tanggal_limbah_masuk', $filters['search_tanggal']);
        }

        if (!empty($filters['search_tanggal_mulai'])) {
            $query->whereDate('tanggal_limbah_masuk', '>=', $filters['search_tanggal_mulai']);
        }

        if (!empty($filters['search_tanggal_akhir'])) {
            $query->whereDate('tanggal_limbah_masuk', '<=', $filters['search_tanggal_akhir']);
        }

        if (!empty($filters['search_kode_identitas'])) {
            $query->where('kode_identitas', 'LIKE', '%' . $filters['search_kode_identitas'] . '%');
        }

        $currentUser = \Illuminate\Support\Facades\Auth::user();
        if ($currentUser && method_exists($currentUser, 'isSuperAdmin') && !$currentUser->isSuperAdmin() && !empty($filters['search_penginput'])) {
            $searchPenginput = $filters['search_penginput'];
            $query->whereHas('penggunaSistem', function ($q) use ($searchPenginput) {
                $q->where('nama_lengkap', 'LIKE', '%' . $searchPenginput . '%')
                    ->orWhere('email_address', 'LIKE', '%' . $searchPenginput . '%');
            });
        }

        if (!empty($filters['expiry_days_min']) || !empty($filters['expiry_days_max'])) {
            $coalesceExpr = 'COALESCE(tanggal_kadaluarsa, maksimal_penyimpanan_tanggal)';
            if (!empty($filters['expiry_days_min'])) {
                $minDays = (int) $filters['expiry_days_min'];
                $query->whereRaw("DATEDIFF($coalesceExpr, CURRENT_DATE) >= ?", [$minDays]);
            }
            if (!empty($filters['expiry_days_max'])) {
                $maxDays = (int) $filters['expiry_days_max'];
                $query->whereRaw("DATEDIFF($coalesceExpr, CURRENT_DATE) <= ?", [$maxDays]);
            }
        }

        $maxRows = \App\Models\ApplicationSetting::getValue('report.max_export_rows', 10000);

        return $query->orderBy('tanggal_limbah_masuk', 'desc')->limit($maxRows)->get();
    }
}
