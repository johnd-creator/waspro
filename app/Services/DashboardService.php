<?php

namespace App\Services;

use App\Models\ApplicationSetting;
use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use App\Models\UnitPembangkit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardService
{
    protected $isLiteMode = false;
    protected $dashboardWindowMonths = 6;
    protected $cacheTTL = 300; // 5 minutes

    public function getDashboardData(array $filters): array
    {
        $this->initializeSettings();

        return [
            'statistics' => $this->getStatistics($filters),
            'charts' => $this->getChartData($filters),
            'recentLogs' => $this->getRecentLogs($filters),
            'expiryWarnings' => $this->getExpiryWarnings($filters),
            'lite_mode' => $this->isLiteMode,
            'isSuperAdmin' => $this->isSuperAdmin(),
        ];
    }

    protected function initializeSettings(): void
    {
        $this->dashboardWindowMonths = (int) ApplicationSetting::getValue('dashboard_window_months', 6);

        $totalLogsCount = LogPenyimpananLimbah::count();
        $liteModeThreshold = (int) ApplicationSetting::getValue('dashboard_lite_mode_threshold', 10000);
        $this->isLiteMode = $totalLogsCount > $liteModeThreshold;
    }

    protected function getStatistics(array $filters): array
    {
        $cacheKey = $this->getCacheKey('statistics', $filters);

        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($filters) {
            $logQuery = $this->buildLogQuery($filters);

            $logStats = $logQuery->leftJoin('jenis_limbah', 'jenis_limbah.kode_limbah', '=', 'log_penyimpanan_limbah.kode_limbah')
                ->selectRaw('
                    COUNT(*) as total_logs,
                    SUM(CASE WHEN status_log = "Tersimpan" THEN 1 ELSE 0 END) as stored_logs,
                    SUM(CASE WHEN status_log = "Diangkut" THEN 1 ELSE 0 END) as transported_logs,
                    SUM(CASE WHEN status_log = "Kadaluarsa" THEN 1 ELSE 0 END) as expired_logs,
                    SUM(CASE WHEN status_log = "Tersimpan" AND tanggal_kadaluarsa >= CURRENT_DATE AND tanggal_kadaluarsa <= DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY) THEN 1 ELSE 0 END) as near_expiry,
                    SUM(CASE WHEN status_log = "Tersimpan" THEN (jumlah_limbah_masuk * COALESCE(jenis_limbah.biaya_pengangkutan_per_kg, 0)) ELSE 0 END) as estimated_cost,
                    SUM(CASE WHEN status_log = "Diangkut" THEN (jumlah_limbah_masuk * COALESCE(jenis_limbah.biaya_pengangkutan_per_kg, 0)) ELSE 0 END) as transported_cost
                ')->first();

            return [
                'total_logs' => (int) $logStats->total_logs,
                'stored_logs' => (int) $logStats->stored_logs,
                'transported_logs' => (int) $logStats->transported_logs,
                'expired_logs' => (int) $logStats->expired_logs,
                'near_expiry' => (int) $logStats->near_expiry,
                'estimated_cost' => (float) ($logStats->estimated_cost ?? 0),
                'transported_cost' => (float) ($logStats->transported_cost ?? 0),
                'total_users' => $this->buildUserQuery($filters)->count(),
                'active_waste_types' => JenisLimbah::where('status_aktif', true)->count(),
            ];
        });
    }

    protected function getChartData(array $filters): array
    {
        $cacheKey = $this->getCacheKey('charts', $filters);

        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($filters) {
            return [
                'waste_by_type' => $this->getWasteByType($filters),
                'waste_by_month' => $this->getWasteByMonth($filters),
                'waste_by_company' => $this->getWasteByCompany($filters),
            ];
        });
    }

    public function getRecentLogs(array $filters): array
    {
        $cacheKey = $this->getCacheKey('recent_logs', $filters);

        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($filters) {
            $query = $this->buildLogQuery($filters)
                ->with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit'])
                ->orderBy('tanggal_limbah_masuk', 'desc');

            if ($this->isLiteMode) {
                return $query->limit(10)->get()->toArray();
            }

            return $query->limit(20)->get()->toArray();
        });
    }

    public function getExpiryWarnings(array $filters): array
    {
        $cacheKey = $this->getCacheKey('expiry_warnings', $filters);

        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($filters) {
            return $this->buildLogQuery($filters)
                ->where('status_log', 'Tersimpan')
                ->whereBetween('tanggal_kadaluarsa', [
                    Carbon::now(),
                    Carbon::now()->addDays(7)
                ])
                ->with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit'])
                ->orderBy('tanggal_kadaluarsa')
                ->limit(10)
                ->get()
                ->toArray();
        });
    }

    public function getWasteByType(array $filters): array
    {
        $aggregationStartDate = Carbon::now()->subMonths($this->dashboardWindowMonths);

        return $this->buildLogQuery($filters)
            ->where('tanggal_limbah_masuk', '>=', $aggregationStartDate)
            ->selectRaw('
                COALESCE(jenis_limbah.nama_limbah, "Tidak Diketahui") as nama_limbah,
                SUM(jumlah_limbah_masuk) as total_jumlah,
                COUNT(*) as total_logs
            ')
            ->leftJoin('jenis_limbah', 'jenis_limbah.kode_limbah', '=', 'log_penyimpanan_limbah.kode_limbah')
            ->groupBy('jenis_limbah.kode_limbah', 'jenis_limbah.nama_limbah')
            ->orderByDesc('total_jumlah')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function getWasteByMonth(array $filters): array
    {
        $aggregationStartDate = Carbon::now()->subMonths($this->dashboardWindowMonths);

        return $this->buildLogQuery($filters)
            ->where('tanggal_limbah_masuk', '>=', $aggregationStartDate)
            ->selectRaw('
                DATE_FORMAT(tanggal_limbah_masuk, "%Y-%m") as bulan,
                SUM(jumlah_limbah_masuk) as total_jumlah,
                COUNT(*) as total_logs
            ')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->toArray();
    }

    public function getWasteByCompany(array $filters): array
    {
        $aggregationStartDate = Carbon::now()->subMonths($this->dashboardWindowMonths);

        return $this->buildLogQuery($filters)
            ->where('tanggal_limbah_masuk', '>=', $aggregationStartDate)
            ->whereNotNull('log_penyimpanan_limbah.perusahaan_id')
            ->selectRaw('
                perusahaan_penghasil.nama_perusahaan,
                SUM(jumlah_limbah_masuk) as total_jumlah,
                COUNT(*) as total_logs
            ')
            ->leftJoin('perusahaan_penghasil', 'perusahaan_penghasil.perusahaan_id', '=', 'log_penyimpanan_limbah.perusahaan_id')
            ->groupBy('perusahaan_penghasil.perusahaan_id', 'perusahaan_penghasil.nama_perusahaan')
            ->orderByDesc('total_jumlah')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function buildLogQuery(array $filters)
    {
        $query = LogPenyimpananLimbah::query();
        $user = Auth::guard('web')->user();
        $isSuperAdmin = $user ? $user->isSuperAdmin() : false;

        if (!$isSuperAdmin && $user && $user->unit_id) {
            $query->where('unit_id', $user->unit_id);
        }

        if ($isSuperAdmin && isset($filters['unit_id']) && $filters['unit_id']) {
            $query->where('unit_id', $filters['unit_id']);
        }

        return $query;
    }

    public function buildUserQuery(array $filters)
    {
        $query = PenggunaSistem::query();
        $user = Auth::guard('web')->user();
        $isSuperAdmin = $user ? $user->isSuperAdmin() : false;

        if (!$isSuperAdmin && $user && $user->unit_id) {
            $query->where('unit_id', $user->unit_id);
        }

        if ($isSuperAdmin && isset($filters['unit_id']) && $filters['unit_id']) {
            $query->where('unit_id', $filters['unit_id']);
        }

        return $query;
    }

    protected function getCacheKey(string $type, array $filters): string
    {
        $unitId = $filters['unit_id'] ?? null;
        $suffix = $unitId ? "unit_{$unitId}" : 'global';

        return "dashboard_{$type}_{$suffix}";
    }

    protected function isSuperAdmin(): bool
    {
        $user = Auth::guard('web')->user();
        return $user ? $user->isSuperAdmin() : false;
    }

    public function clearCache(array $filters = []): void
    {
        $suffix = isset($filters['unit_id']) ? "unit_{$filters['unit_id']}" : 'global';

        Cache::forget("dashboard_statistics_{$suffix}");
        Cache::forget("dashboard_charts_{$suffix}");
        Cache::forget("dashboard_recent_logs_{$suffix}");
        Cache::forget("dashboard_expiry_warnings_{$suffix}");
    }

    public function getUnits(): \Illuminate\Support\Collection
    {
        return UnitPembangkit::orderBy('nama_unit')
            ->get(['unit_id', 'nama_unit']);
    }
}
