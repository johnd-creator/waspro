<?php

namespace App\Http\Controllers;

use App\Models\ApplicationSetting;
use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics and charts
     */
    public function index(Request $request)
    {
        // Debug logging
        \Log::info('DashboardController index method called', [
            'request_url' => request()->url(),
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
        ]);

        $startMemory = memory_get_usage();

        // Measurement helper for query profiling
        $measure = function ($label, callable $callback) {
            $memBefore = memory_get_usage();
            $timeBefore = microtime(true);

            $result = $callback();

            $timeAfter = microtime(true);
            $memAfter = memory_get_usage();
            $peakMem = memory_get_peak_usage();

            \Log::info('[QUERY_PERF] ' . $label, [
                'duration_ms' => round(($timeAfter - $timeBefore) * 1000, 2),
                'memory_usage_mb' => round(($memAfter - $memBefore) / 1024 / 1024, 2),
                'peak_memory_mb' => round($peakMem / 1024 / 1024, 2),
            ]);

            return $result;
        };

        $user = Auth::guard('web')->user();

        // Super Admin Filter Logic
        $isSuperAdmin = $user ? $user->isSuperAdmin() : false;
        $units = [];
        $selectedUnitId = $request->input('unit_id');
        $selectedUnitName = 'Global';

        // C1: Dashboard configuration - time window for aggregations
        $dashboardWindowMonths = (int) ApplicationSetting::getValue('dashboard_window_months', 6);
        $aggregationStartDate = Carbon::now()->subMonths($dashboardWindowMonths);

        // C3: Early Lite Mode detection (before running queries)
        $totalLogsCount = LogPenyimpananLimbah::count();
        $liteModeThreshold = (int) ApplicationSetting::getValue('dashboard_lite_mode_threshold', 10000);
        $isLiteMode = $totalLogsCount > $liteModeThreshold || $request->input('lite') === '1';

        \Log::info('[DASHBOARD_MODE]', [
            'total_logs' => $totalLogsCount,
            'threshold' => $liteModeThreshold,
            'is_lite_mode' => $isLiteMode,
        ]);

        if ($isSuperAdmin) {
            // Units list is master data, usually stable. Can use existing CacheService master data method if desired.
            // keeping it direct for now to focus on dashboard stats stability.
            $units = $measure('units_list', function () {
                return \App\Models\UnitPembangkit::orderBy('nama_unit')->get();
            });
            if ($selectedUnitId) {
                $unit = $units->find($selectedUnitId);
                $selectedUnitName = $unit ? $unit->nama_unit : 'Global';
            }
        }

        // Determine cache key suffix based on filters
        $cacheSuffix = $selectedUnitId ? "unit_{$selectedUnitId}" : 'global';

        // Determine cache key based on filters
        $cacheKey = $selectedUnitId ? "dashboard_unit_{$selectedUnitId}" : "dashboard_global";

        // Initial Queries - Removed Cache::remember wrapper
        // Note: Caching disabled per stabilization plan (Plan A)
        $logQuery = LogPenyimpananLimbah::query();
        $storedLogsQuery = LogPenyimpananLimbah::where('status_log', 'Tersimpan');
        $transportedLogsQuery = LogPenyimpananLimbah::where('status_log', 'Diangkut');
        $usersQuery = PenggunaSistem::query();

        // Apply filters if Super Admin selected a unit
        if ($isSuperAdmin && $selectedUnitId) {
            $logQuery->where('unit_id', $selectedUnitId);
            $storedLogsQuery->where('unit_id', $selectedUnitId);
            $transportedLogsQuery->where('unit_id', $selectedUnitId);
            $usersQuery->where('unit_id', $selectedUnitId);
        }

        $totalLogs = $measure('count_total_logs', fn() => $logQuery->count());
        $totalWasteTypes = $measure('count_waste_types', fn() => JenisLimbah::count());
        $totalStoredLogs = $measure('count_stored_logs', fn() => $storedLogsQuery->count());
        $totalTransported = $measure('count_transported', fn() => $transportedLogsQuery->count());
        $totalUsers = $measure('count_users', fn() => $usersQuery->count());

        // Status Distribution
        $statusDistribution = $measure('status_distribution', function () use ($isSuperAdmin, $selectedUnitId) {
            $statusDistQuery = LogPenyimpananLimbah::select('status_log', DB::raw('count(*) as total'));
            if ($isSuperAdmin && $selectedUnitId) {
                $statusDistQuery->where('unit_id', $selectedUnitId);
            }
            return $statusDistQuery->groupBy('status_log')->get();
        });

        // Monthly Data
        $monthlyData = $measure('monthly_data', function () use ($isSuperAdmin, $selectedUnitId) {
            $monthlyDataQuery = LogPenyimpananLimbah::select(
                DB::raw('strftime("%Y", tanggal_limbah_masuk) as year'),
                DB::raw('strftime("%m", tanggal_limbah_masuk) as month'),
                DB::raw('SUM(jumlah_limbah_masuk) as total_waste'),
                DB::raw('COUNT(*) as total_logs')
            )
                ->where('tanggal_limbah_masuk', '>=', Carbon::now()->subMonths(12));

            if ($isSuperAdmin && $selectedUnitId) {
                $monthlyDataQuery->where('unit_id', $selectedUnitId);
            }

            // Limit monthly data to last 12 months in case global filter is applied
            return $monthlyDataQuery->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get();
        });

        // Top Waste Types (C1: Added time window)
        $topWasteTypes = $measure('top_waste_types', function () use ($isSuperAdmin, $selectedUnitId, $aggregationStartDate, $isLiteMode) {
            if ($isLiteMode) {
                return collect(); // Skip in Lite Mode
            }

            $topWasteTypesQuery = LogPenyimpananLimbah::select(
                'jenis_limbah.nama_limbah',
                DB::raw('SUM(jumlah_limbah_masuk) as total_quantity'),
                DB::raw('COUNT(*) as total_logs')
            )
                ->join('jenis_limbah', 'log_penyimpanan_limbah.kode_limbah', '=', 'jenis_limbah.kode_limbah')
                ->where('log_penyimpanan_limbah.tanggal_limbah_masuk', '>=', $aggregationStartDate);

            if ($isSuperAdmin && $selectedUnitId) {
                $topWasteTypesQuery->where('log_penyimpanan_limbah.unit_id', $selectedUnitId);
            }

            return $topWasteTypesQuery->groupBy('jenis_limbah.kode_limbah', 'jenis_limbah.nama_limbah')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->get();
        });

        // Top Companies (C1: Added time window)
        $topCompanies = $measure('top_companies', function () use ($isSuperAdmin, $selectedUnitId, $aggregationStartDate, $isLiteMode) {
            if ($isLiteMode) {
                return collect(); // Skip in Lite Mode
            }

            $topCompaniesQuery = LogPenyimpananLimbah::select(
                'perusahaan_penghasil.nama_perusahaan',
                DB::raw('SUM(jumlah_limbah_masuk) as total_quantity'),
                DB::raw('COUNT(*) as total_logs')
            )
                ->join('perusahaan_penghasil', 'log_penyimpanan_limbah.perusahaan_id', '=', 'perusahaan_penghasil.perusahaan_id')
                ->whereNotNull('log_penyimpanan_limbah.perusahaan_id')
                ->where('log_penyimpanan_limbah.tanggal_limbah_masuk', '>=', $aggregationStartDate);

            if ($isSuperAdmin && $selectedUnitId) {
                $topCompaniesQuery->where('log_penyimpanan_limbah.unit_id', $selectedUnitId);
            }

            return $topCompaniesQuery->groupBy('perusahaan_penghasil.perusahaan_id', 'perusahaan_penghasil.nama_perusahaan')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->get();
        });

        // Waste By Branch (C1: Added time window)
        $wasteByBranch = $measure('waste_by_branch', function () use ($isSuperAdmin, $selectedUnitId, $aggregationStartDate, $isLiteMode) {
            if ($isLiteMode) {
                return collect(); // Skip in Lite Mode
            }

            $wasteByBranchQuery = LogPenyimpananLimbah::select(
                'unit_pembangkit.nama_unit',
                DB::raw('SUM(jumlah_limbah_masuk) as total_quantity'),
                DB::raw('COUNT(*) as total_logs')
            )
                ->join('unit_pembangkit', 'log_penyimpanan_limbah.unit_id', '=', 'unit_pembangkit.unit_id')
                ->where('log_penyimpanan_limbah.tanggal_limbah_masuk', '>=', $aggregationStartDate);

            if ($isSuperAdmin && $selectedUnitId) {
                $wasteByBranchQuery->where('log_penyimpanan_limbah.unit_id', $selectedUnitId);
            }

            // Added LIMIT 10 to prevent unbounded growth of branch list
            return $wasteByBranchQuery->groupBy('unit_pembangkit.unit_id', 'unit_pembangkit.nama_unit')
                ->orderBy('total_quantity', 'desc')
                ->limit(10)
                ->get();
        });

        // Near Expiry (Memory-Optimized: No Eager Loading)
        list($nearExpiryWaste, $totalNearExpiry) = $measure('near_expiry_waste', function () use ($isSuperAdmin, $selectedUnitId) {
            $warningDays = (int) ApplicationSetting::getValue('warning_days', 30);
            $cutoffDate = Carbon::now()->addDays($warningDays);

            $storedWasteQuery = LogPenyimpananLimbah::select(
                'log_penyimpanan_limbah.log_id',
                'log_penyimpanan_limbah.tanggal_limbah_masuk',
                'log_penyimpanan_limbah.jumlah_limbah_masuk',
                'log_penyimpanan_limbah.maksimal_penyimpanan_tanggal',
                'jenis_limbah.nama_limbah',
                'perusahaan_penghasil.nama_perusahaan',
                'unit_pembangkit.nama_unit'
            )
                ->join('jenis_limbah', 'log_penyimpanan_limbah.kode_limbah', '=', 'jenis_limbah.kode_limbah')
                ->leftJoin('perusahaan_penghasil', 'log_penyimpanan_limbah.perusahaan_id', '=', 'perusahaan_penghasil.perusahaan_id')
                ->join('unit_pembangkit', 'log_penyimpanan_limbah.unit_id', '=', 'unit_pembangkit.unit_id')
                ->where('status_log', 'Tersimpan');

            if ($isSuperAdmin && $selectedUnitId) {
                $storedWasteQuery->where('log_penyimpanan_limbah.unit_id', $selectedUnitId);
            }

            $nearExpiryWaste = $storedWasteQuery
                ->where('maksimal_penyimpanan_tanggal', '>', Carbon::now())
                ->where('maksimal_penyimpanan_tanggal', '<=', $cutoffDate)
                ->orderBy('maksimal_penyimpanan_tanggal', 'asc')
                ->limit(10)
                ->get();

            $totalNearExpiry = $nearExpiryWaste->count(); // Count of shown items

            return [$nearExpiryWaste, $totalNearExpiry];
        });

        // Recent Activities (Memory-Optimized: No Eager Loading)
        list($recentActivities, $recentActivitiesCount) = $measure('recent_activities', function () use ($isSuperAdmin, $selectedUnitId) {
            $recentActivitiesQuery = LogPenyimpananLimbah::select(
                'log_penyimpanan_limbah.log_id',
                'log_penyimpanan_limbah.tanggal_limbah_masuk',
                'log_penyimpanan_limbah.jumlah_limbah_masuk',
                'log_penyimpanan_limbah.status_log',
                'log_penyimpanan_limbah.created_at',
                'jenis_limbah.nama_limbah',
                'perusahaan_penghasil.nama_perusahaan',
                'pengguna_sistem.nama_lengkap as user_name'
            )
                ->with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit', 'penggunaSistem'])
                ->join('jenis_limbah', 'log_penyimpanan_limbah.kode_limbah', '=', 'jenis_limbah.kode_limbah')
                ->leftJoin('perusahaan_penghasil', 'log_penyimpanan_limbah.perusahaan_id', '=', 'perusahaan_penghasil.perusahaan_id')
                ->leftJoin('pengguna_sistem', 'log_penyimpanan_limbah.user_id', '=', 'pengguna_sistem.user_id')
                ->where('log_penyimpanan_limbah.created_at', '>=', Carbon::now()->subHours(24));

            if ($isSuperAdmin && $selectedUnitId) {
                $recentActivitiesQuery->where('log_penyimpanan_limbah.unit_id', $selectedUnitId);
            }

            $recentActivities = $recentActivitiesQuery->orderBy('log_penyimpanan_limbah.created_at', 'desc')
                ->limit(20)
                ->get();

            $recentActivitiesCount = $recentActivities->count();

            return [$recentActivities, $recentActivitiesCount];
        });

        // --- Observability & Guardrails (A3) ---
        $endMemory = memory_get_usage();
        $peakMemory = memory_get_peak_usage();
        $memoryUsageMB = round(($endMemory - $startMemory) / 1024 / 1024, 2);
        $peakMemoryMB = round($peakMemory / 1024 / 1024, 2);

        // Define Thresholds
        $warningThresholdMB = 64;
        $isLiteMode = false;

        if ($memoryUsageMB > $warningThresholdMB) {
            $isLiteMode = true;
            \Log::warning("[DASHBOARD_PERF] Memory Usage High. Activating Lite Mode.", [
                'usage_mb' => $memoryUsageMB,
                'peak_mb' => $peakMemoryMB,
                'threshold_mb' => $warningThresholdMB
            ]);
        } else {
            \Log::info("[DASHBOARD_PERF] Dashboard Render Stats", [
                'route' => '/dashboard',
                'usage_mb' => $memoryUsageMB,
                'peak_mb' => $peakMemoryMB,
                'is_lite_mode' => $isLiteMode
            ]);
        }

        // Settings
        $warningDays = (int) ApplicationSetting::getValue('warning_days', 30);

        // --- View Presentation Logic (Status Charts, Monthly Chart Formats) ---
        // Calculate Status Charts
        $statusTotals = $statusDistribution->pluck('total', 'status_log');
        $statusOrder = ['Tersimpan', 'Diangkut', 'Kadaluarsa'];
        $defaultColor = [
            'dot' => 'bg-slate-400',
            'text' => 'text-slate-600',
            'background' => 'rgba(148, 163, 184, 0.8)',
            'border' => 'rgba(148, 163, 184, 1)',
        ];
        $statusColorMap = [
            'Tersimpan' => ['dot' => 'bg-blue-500', 'text' => 'text-blue-600', 'background' => 'rgba(59, 130, 246, 0.8)', 'border' => 'rgba(59, 130, 246, 1)'],
            'Diangkut' => ['dot' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'background' => 'rgba(16, 185, 129, 0.8)', 'border' => 'rgba(16, 185, 129, 1)'],
            'Kadaluarsa' => ['dot' => 'bg-red-500', 'text' => 'text-red-600', 'background' => 'rgba(239, 68, 68, 0.8)', 'border' => 'rgba(239, 68, 68, 1)'],
        ];

        $statusChartLabels = [];
        $statusChartValues = [];
        $statusChartBackgroundColors = [];
        $statusChartBorderColors = [];
        $statusSummary = [];
        $statusTotalCount = (int) $statusTotals->sum();

        $appendStatusData = function (string $status) use ($statusTotals, $statusColorMap, $defaultColor, &$statusChartLabels, &$statusChartValues, &$statusChartBackgroundColors, &$statusChartBorderColors, &$statusSummary, $statusTotalCount) {
            $count = (int) ($statusTotals[$status] ?? 0);
            $colors = $statusColorMap[$status] ?? $defaultColor;
            $statusChartLabels[] = $status;
            $statusChartValues[] = $count;
            $statusChartBackgroundColors[] = $colors['background'];
            $statusChartBorderColors[] = $colors['border'];
            $statusSummary[] = [
                'label' => $status,
                'count' => $count,
                'percentage' => $statusTotalCount > 0 ? round(($count / $statusTotalCount) * 100) : 0,
                'dot_class' => $colors['dot'],
                'text_class' => $colors['text'],
            ];
        };

        foreach ($statusOrder as $status)
            $appendStatusData($status);
        foreach ($statusTotals->keys() as $status) {
            if (!in_array($status, $statusOrder, true))
                $appendStatusData($status);
        }

        // Calculate Monthly Charts
        $monthlyChartLabels = [];
        $monthlyChartValues = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $year = $date->format('Y');
            $month = $date->format('m');
            $monthlyChartLabels[] = $date->translatedFormat('M y');
            $match = $monthlyData->first(function ($item) use ($year, $month) {
                return $item->year === $year && $item->month === $month;
            });
            $monthlyChartValues[] = $match ? (float) $match->total_waste : 0;
        }

        return view('dashboard', compact(
            'totalLogs',
            'totalWasteTypes',
            'totalStoredLogs',
            'totalTransported',
            'totalUsers',
            'totalNearExpiry',
            'statusDistribution',
            'statusSummary',
            'statusChartLabels',
            'statusChartValues',
            'statusChartBackgroundColors',
            'statusChartBorderColors',
            'monthlyData',
            'monthlyChartLabels',
            'monthlyChartValues',
            'topWasteTypes',
            'topCompanies',
            'wasteByBranch',
            'nearExpiryWaste',
            'recentActivities',
            'recentActivitiesCount',
            'warningDays',
            'isSuperAdmin',
            'units',
            'selectedUnitId',
            'selectedUnitName',
            'isLiteMode'
        ) + ['wasteTypes' => $totalWasteTypes, 'companies' => $totalStoredLogs]);
    }

    /**
     * Get chart data for AJAX requests
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type');
        $unitId = $request->get('unit_id'); // Support unit filter params

        // Security check: Only SuperAdmin can filter by arbitrary unit
        $user = Auth::guard('web')->user();
        if (!$user->isSuperAdmin()) {
            $unitId = null; // Force use user's own unit (via Scope)
        }

        switch ($type) {
            case 'monthly':
                return $this->getMonthlyChartData($unitId);
            case 'status':
                return $this->getStatusChartData($unitId);
            case 'waste-types':
                return $this->getWasteTypesChartData($unitId);
            case 'companies':
                return $this->getCompaniesChartData($unitId);
            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }

    private function getMonthlyChartData($userUnitId = null)
    {
        // UnitScope akan otomatis memfilter berdasarkan unit user
        $query = LogPenyimpananLimbah::select(
            DB::raw('strftime("%Y", tanggal_limbah_masuk) as year'),
            DB::raw('strftime("%m", tanggal_limbah_masuk) as month'),
            DB::raw('SUM(jumlah_limbah_masuk) as total_waste')
        )
            ->where('tanggal_limbah_masuk', '>=', Carbon::now()->subMonths(12));

        if ($userUnitId) {
            $query->where('unit_id', $userUnitId);
        }

        $data = $query->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        return response()->json($data);
    }

    private function getStatusChartData($userUnitId = null)
    {
        $query = LogPenyimpananLimbah::select('status_log', DB::raw('count(*) as total'));

        if ($userUnitId) {
            $query->where('unit_id', $userUnitId);
        }

        $data = $query->groupBy('status_log')
            ->get();

        return response()->json($data);
    }

    private function getWasteTypesChartData($userUnitId = null)
    {
        $query = LogPenyimpananLimbah::select(
            'jenis_limbah.nama_limbah',
            DB::raw('SUM(jumlah_limbah_masuk) as total_quantity')
        )
            ->join('jenis_limbah', 'log_penyimpanan_limbah.kode_limbah', '=', 'jenis_limbah.kode_limbah');

        if ($userUnitId) {
            $query->where('log_penyimpanan_limbah.unit_id', $userUnitId);
        }

        $data = $query->groupBy('jenis_limbah.kode_limbah', 'jenis_limbah.nama_limbah')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        return response()->json($data);
    }

    private function getCompaniesChartData($userUnitId = null)
    {
        $query = LogPenyimpananLimbah::select(
            'perusahaan_penghasil.nama_perusahaan',
            DB::raw('SUM(jumlah_limbah_masuk) as total_quantity')
        )
            ->join('perusahaan_penghasil', 'log_penyimpanan_limbah.perusahaan_id', '=', 'perusahaan_penghasil.perusahaan_id')
            ->whereNotNull('log_penyimpanan_limbah.perusahaan_id');

        if ($userUnitId) {
            $query->where('log_penyimpanan_limbah.unit_id', $userUnitId);
        }

        $data = $query->groupBy('perusahaan_penghasil.perusahaan_id', 'perusahaan_penghasil.nama_perusahaan')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        return response()->json($data);
    }

    public function nearExpiryList(Request $request)
    {
        $sortBy = $request->get('sort', 'days_remaining');
        $sortOrder = $request->get('order', 'asc');

        // Get warning days from settings (default 30 if not set)
        $warningDays = (int) ApplicationSetting::getValue('warning_days', 30);

        $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit'])
            ->where('status_log', 'Tersimpan')
            ->where('maksimal_penyimpanan_tanggal', '<=', Carbon::now()->addDays($warningDays))
            ->where('maksimal_penyimpanan_tanggal', '>', Carbon::now());

        // Add calculated field for days remaining (SQLite compatible)
        $query->selectRaw('*, CAST((julianday(maksimal_penyimpanan_tanggal) - julianday("now")) AS INTEGER) as days_remaining');

        if ($sortBy === 'days_remaining') {
            $query->orderByRaw('CAST((julianday(maksimal_penyimpanan_tanggal) - julianday("now")) AS INTEGER) ' . $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $nearExpiryWaste = $query->paginate(20);

        return view('dashboard.near-expiry', compact('nearExpiryWaste', 'sortBy', 'sortOrder'));
    }
}
