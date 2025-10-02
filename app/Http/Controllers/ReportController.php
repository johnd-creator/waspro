<?php

namespace App\Http\Controllers;

use App\Models\LogPenyimpananLimbah;
use App\Models\PerusahaanPenghasil;
use App\Models\UnitPembangkit;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display the main report dashboard
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Monthly/Yearly Report
     */
    public function monthly(Request $request)
    {
        $request->validate([
            'year' => 'nullable|integer|min:2020|max:'.(date('Y') + 1),
            'month' => 'nullable|integer|min:1|max:12',
            'unit_id' => 'nullable|exists:unit_pembangkit,unit_id',
            'format' => 'nullable|in:view,pdf,excel',
        ]);

        $year = (int) $request->get('year', date('Y'));
        $month = $request->filled('month') ? (int) $request->get('month') : null;
        $unitId = $request->get('unit_id');
        $format = $request->get('format', 'view');

        // Cache key for this report
        $cacheKey = "monthly_report_{$year}_{$month}_{$unitId}_".Auth::user()->unit_id;

        $data = Cache::remember($cacheKey, 3600, function () use ($year, $month, $unitId) {
            $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit'])
                ->whereYear('tanggal_limbah_masuk', $year);

            if ($month) {
                $query->whereMonth('tanggal_limbah_masuk', $month);
            }

            if ($unitId) {
                $query->where('unit_id', $unitId);
            }

            $logs = $query->get();

            // Statistics
            $totalLogs = $logs->count();
            $totalWaste = $logs->sum('jumlah_limbah_masuk');
            $totalTransported = $logs->where('status_log', 'Diangkut')->count();
            $wasteStored = $logs->where('status_log', 'Tersimpan')->count();
            $wasteExpired = $logs->where('status_log', 'Kadaluarsa')->sum('jumlah_limbah_masuk');

            // Monthly breakdown
            $monthlyBreakdown = $logs->groupBy(function ($log) {
                return Carbon::parse($log->tanggal_limbah_masuk)->format('m');
            })->map(function ($monthLogs) {
                return [
                    'total_logs' => $monthLogs->count(),
                    'total_waste' => $monthLogs->sum('jumlah_limbah_masuk'),
                    'transported' => $monthLogs->where('status_log', 'Diangkut')->count(),
                    'stored' => $monthLogs->where('status_log', 'Tersimpan')->count(),
                    'expired' => $monthLogs->where('status_log', 'Kadaluarsa')->sum('jumlah_limbah_masuk'),
                ];
            });

            // Top waste types
            $topWasteTypes = $logs->groupBy('kode_limbah')
                ->map(function ($wasteLogs) {
                    return [
                        'nama_limbah' => $wasteLogs->first()->jenisLimbah->nama_limbah ?? 'Unknown',
                        'total_quantity' => $wasteLogs->sum('jumlah_limbah_masuk'),
                        'total_logs' => $wasteLogs->count(),
                    ];
                })
                ->sortByDesc('total_quantity')
                ->take(10);

            // Top companies
            $topCompanies = $logs->whereNotNull('perusahaan_id')
                ->groupBy('perusahaan_id')
                ->map(function ($companyLogs) {
                    return [
                        'nama_perusahaan' => $companyLogs->first()->perusahaanPenghasil->nama_perusahaan ?? 'Unknown',
                        'total_quantity' => $companyLogs->sum('jumlah_limbah_masuk'),
                        'total_logs' => $companyLogs->count(),
                    ];
                })
                ->sortByDesc('total_quantity')
                ->take(10);

            return compact(
                'totalLogs', 'totalWaste', 'totalTransported', 'wasteStored', 'wasteExpired',
                'monthlyBreakdown', 'topWasteTypes', 'topCompanies', 'logs'
            );
        });

        $data['year'] = $year;
        $data['month'] = $month;
        $data['unitId'] = $unitId;
        $data['monthName'] = $month ? Carbon::create()->month((int) $month)->format('F') : null;

        // All units for filter (filtered by user access)
        $data['units'] = (Auth::user() && Auth::user()->isSuperAdmin())
            ? UnitPembangkit::orderBy('nama_unit')->get()
            : UnitPembangkit::where('unit_id', Auth::user()->unit_id)->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.monthly-pdf', $data);

            return $pdf->download('monthly-report-'.($month ? $data['monthName'].'-' : '').$year.'.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new \App\Exports\MonthlyReportExport($data), 'monthly-report-'.($month ? $data['monthName'].'-' : '').$year.'.xlsx');
        }

        return view('reports.monthly', $data);
    }

    /**
     * Status Report
     */
    public function status(Request $request)
    {
        $request->validate([
            'status' => 'nullable|in:Tersimpan,Diangkut,Kadaluarsa',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'format' => 'nullable|in:view,pdf,excel',
        ]);

        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $format = $request->get('format', 'view');

        $cacheKey = "status_report_{$status}_".md5($dateFrom.$dateTo).'_'.Auth::user()->unit_id;

        $data = Cache::remember($cacheKey, 1800, function () use ($status, $dateFrom, $dateTo) {
            $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit']);

            if ($status) {
                $query->where('status_log', $status);
            }

            if ($dateFrom) {
                $query->whereDate('tanggal_limbah_masuk', '>=', $dateFrom);
            }

            if ($dateTo) {
                $query->whereDate('tanggal_limbah_masuk', '<=', $dateTo);
            }

            $logs = $query->get();

            // Status distribution
            $statusDistribution = $logs->groupBy('status_log')->map(function ($group) use ($logs) {
                $count = $group->count();
                $totalQuantity = $group->sum('jumlah_limbah_masuk');

                return [
                    'count' => $count,
                    'total_quantity' => $totalQuantity,
                    'percentage' => $logs->count() > 0 ? round(($count / $logs->count()) * 100, 2) : 0,
                ];
            });

            // Prepare data for chart
            $chartData = [
                'labels' => ['Tersimpan', 'Diangkut', 'Kadaluarsa'],
                'datasets' => [
                    [
                        'label' => 'Jumlah Log',
                        'backgroundColor' => ['#f59e0b', '#10b981', '#ef4444'],
                        'data' => [
                            $statusDistribution['Tersimpan']['count'] ?? 0,
                            $statusDistribution['Diangkut']['count'] ?? 0,
                            $statusDistribution['Kadaluarsa']['count'] ?? 0,
                        ],
                    ],
                ],
            ];

            return compact('logs', 'statusDistribution', 'chartData');
        });

        $data['status'] = $status;
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.status-pdf', $data);

            return $pdf->download('status-report-'.($status ?: 'all').'.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new \App\Exports\StatusReportExport($data), 'status-report-'.($status ?: 'all').'.xlsx');
        }

        return view('reports.status', $data);
    }

    /**
     * Waste Type Report
     */
    public function wasteType(Request $request)
    {
        $request->validate([
            'jenis_limbah_id' => 'nullable|exists:jenis_limbah,jenis_limbah_id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'format' => 'nullable|in:view,pdf,excel',
        ]);

        $jenisLimbahId = $request->get('jenis_limbah_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $format = $request->get('format', 'view');

        $cacheKey = "waste_type_report_{$jenisLimbahId}_".md5($dateFrom.$dateTo).'_'.Auth::user()->unit_id;

        $data = Cache::remember($cacheKey, 1800, function () use ($jenisLimbahId, $dateFrom, $dateTo) {
            $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit']);

            if ($jenisLimbahId) {
                $query->where('kode_limbah', $jenisLimbahId);
            }

            if ($dateFrom) {
                $query->whereDate('tanggal_limbah_masuk', '>=', $dateFrom);
            }

            if ($dateTo) {
                $query->whereDate('tanggal_limbah_masuk', '<=', $dateTo);
            }

            $logs = $query->get();

            // Waste type distribution
            $wasteTypeDistribution = $logs->groupBy('kode_limbah')->map(function ($group) {
                return [
                    'nama_limbah' => $group->first()->jenisLimbah->nama_limbah ?? 'Unknown',
                    'total_quantity' => $group->sum('jumlah_limbah_masuk'),
                    'total_logs' => $group->count(),
                ];
            })->sortByDesc('total_quantity');

            return compact('logs', 'wasteTypeDistribution');
        });

        $data['jenisLimbahId'] = $jenisLimbahId;
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;

        // Add waste types for filter dropdown
        $data['wasteTypes'] = \App\Models\JenisLimbah::where('status_aktif', true)->orderBy('nama_limbah')->get();

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.waste-type-pdf', $data);

            return $pdf->download('waste-type-report-'.($jenisLimbahId ?: 'all').'.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new \App\Exports\WasteTypeReportExport($data), 'waste-type-report-'.($jenisLimbahId ?: 'all').'.xlsx');
        }

        return view('reports.waste-type', $data);
    }

    /**
     * Company Report
     */
    public function company(Request $request)
    {
        $request->validate([
            'perusahaan_id' => 'nullable|exists:perusahaan_penghasil,perusahaan_id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'format' => 'nullable|in:view,pdf,excel',
        ]);

        $perusahaanId = $request->get('perusahaan_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $format = $request->get('format', 'view');

        $cacheKey = "company_report_{$perusahaanId}_".md5($dateFrom.$dateTo).'_'.Auth::user()->unit_id;

        $data = Cache::remember($cacheKey, 1800, function () use ($perusahaanId, $dateFrom, $dateTo) {
            $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit']);

            if ($perusahaanId) {
                $query->where('perusahaan_id', $perusahaanId);
            }

            if ($dateFrom) {
                $query->whereDate('tanggal_limbah_masuk', '>=', $dateFrom);
            }

            if ($dateTo) {
                $query->whereDate('tanggal_limbah_masuk', '<=', $dateTo);
            }

            $logs = $query->get();

            // Company statistics
            $companyStats = $logs->whereNotNull('perusahaan_id')
                ->groupBy('perusahaan_id')
                ->map(function ($companyLogs) {
                    $perusahaan = $companyLogs->first()->perusahaanPenghasil;

                    return [
                        'perusahaan_id' => $perusahaan->perusahaan_id ?? null,
                        'nama_perusahaan' => $perusahaan->nama_perusahaan ?? 'Unknown',
                        'jenis_perusahaan' => $perusahaan->jenis_perusahaan ?? 'Unknown',
                        'total_logs' => $companyLogs->count(),
                        'total_quantity' => $companyLogs->sum('jumlah_limbah_masuk'),
                        'avg_monthly_quantity' => $companyLogs->sum('jumlah_limbah_masuk') / max(1, $companyLogs->groupBy(function ($log) {
                            return Carbon::parse($log->tanggal_limbah_masuk)->format('Y-m');
                        })->count()),
                        'waste_types' => $companyLogs->groupBy('kode_limbah')->map(function ($wasteLogs) {
                            return [
                                'nama_limbah' => $wasteLogs->first()->jenisLimbah->nama_limbah ?? 'Unknown',
                                'quantity' => $wasteLogs->sum('jumlah_limbah_masuk'),
                                'logs_count' => $wasteLogs->count(),
                            ];
                        })->sortByDesc('quantity'),
                        'status_breakdown' => $companyLogs->groupBy('status_log')->map->count(),
                    ];
                })
                ->sortByDesc('total_quantity');

            // All companies for filter
            $companies = PerusahaanPenghasil::orderBy('nama_perusahaan')->get();

            return compact('logs', 'companyStats', 'companies');
        });

        $data['perusahaanId'] = $perusahaanId;
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.company-pdf', $data);

            return $pdf->download('company-report-'.($perusahaanId ?: 'all').'.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new \App\Exports\CompanyReportExport($data), 'company-report-'.($perusahaanId ?: 'all').'.xlsx');
        }

        return view('reports.company', $data);
    }

    /**
     * Unit Report
     */
    public function unit(Request $request)
    {
        $request->validate([
            'unit_id' => 'nullable|exists:unit_pembangkit,unit_id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'format' => 'nullable|in:view,pdf,excel',
        ]);

        $unitId = $request->get('unit_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $format = $request->get('format', 'view');

        // Only Super Admin can see all units
        if ((! Auth::user() || ! Auth::user()->isSuperAdmin()) && $unitId && $unitId != Auth::user()->unit_id) {
            abort(403, 'Unauthorized access to unit data.');
        }

        $cacheKey = "unit_report_{$unitId}_".md5($dateFrom.$dateTo).'_'.Auth::user()->unit_id;

        $data = Cache::remember($cacheKey, 1800, function () use ($unitId, $dateFrom, $dateTo) {
            $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit']);

            if ($unitId) {
                $query->where('unit_id', $unitId);
            }

            if ($dateFrom) {
                $query->whereDate('tanggal_limbah_masuk', '>=', $dateFrom);
            }

            if ($dateTo) {
                $query->whereDate('tanggal_limbah_masuk', '<=', $dateTo);
            }

            $logs = $query->get();

            // Unit statistics
            $unitStats = $logs->groupBy('unit_id')
                ->map(function ($unitLogs) {
                    $unit = $unitLogs->first()->unitPembangkit;

                    return [
                        'unit_id' => $unit->unit_id ?? null,
                        'nama_unit' => $unit->nama_unit ?? 'Unknown',
                        'lokasi' => $unit->lokasi ?? 'Unknown',
                        'total_logs' => $unitLogs->count(),
                        'total_quantity' => $unitLogs->sum('jumlah_limbah_masuk'),
                        'efficiency_rate' => $unitLogs->where('status_log', 'Diangkut')->count() / max(1, $unitLogs->count()) * 100,
                        'avg_storage_days' => $unitLogs->avg(function ($log) {
                            return Carbon::parse($log->tanggal_limbah_masuk)->diffInDays(
                                $log->tanggal_pengangkutan ? Carbon::parse($log->tanggal_pengangkutan) : Carbon::now()
                            );
                        }),
                        'waste_types' => $unitLogs->groupBy('kode_limbah')->map(function ($wasteLogs) {
                            return [
                                'nama_limbah' => $wasteLogs->first()->jenisLimbah->nama_limbah ?? 'Unknown',
                                'quantity' => $wasteLogs->sum('jumlah_limbah_masuk'),
                                'logs_count' => $wasteLogs->count(),
                            ];
                        })->sortByDesc('quantity'),
                        'status_breakdown' => $unitLogs->groupBy('status_log')->map->count(),
                    ];
                })
                ->sortByDesc('total_quantity');

            // All units for filter (filtered by user access)
            $units = (Auth::user() && Auth::user()->isSuperAdmin())
                ? UnitPembangkit::orderBy('nama_unit')->get()
                : UnitPembangkit::where('unit_id', Auth::user()->unit_id)->get();

            return compact('logs', 'unitStats', 'units');
        });

        $data['unitId'] = $unitId;
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('reports.unit-pdf', $data);

            return $pdf->download('unit-report-'.($unitId ?: 'all').'.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(new \App\Exports\UnitReportExport($data), 'unit-report-'.($unitId ?: 'all').'.xlsx');
        }

        return view('reports.unit', $data);
    }

    /**
     * Clear report cache
     */
    public function clearCache(Request $request)
    {
        $user = Auth::user();
        $isSuper = $user && method_exists($user, 'isSuperAdmin') ? $user->isSuperAdmin() : false;
        $unitId = $isSuper ? null : ($user->unit_id ?? null);

        $ok = $this->clearReportCache($unitId, $isSuper);

        if ($request->expectsJson()) {
            if (! $ok) {
                return response()->json(['success' => false, 'error' => 'Store cache tidak mendukung penghapusan parsial, hubungi admin.'], 422);
            }

            return response()->json(['success' => true]);
        }

        if (! $ok) {
            return redirect()->back()->with('error', 'Gagal menghapus cache: store tidak mendukung penghapusan parsial.');
        }

        return redirect()->back()->with('success', 'Cache report berhasil dibersihkan.');
    }

    /**
     * Clear specific report cache patterns
     *
     * @param  int|null  $unitId  Hapus khusus untuk unit tertentu (non-superadmin). Null berarti hapus semua (hanya superadmin)
     * @param  bool  $isSuper  Apakah pemanggil superadmin
     * @return bool Berhasil atau tidak
     */
    private function clearReportCache(?int $unitId = null, bool $isSuper = false): bool
    {
        // Pola umum untuk semua report
        $patterns = [
            'monthly_report_%',
            'status_report_%',
            'waste_type_report_%',
            'company_report_%',
            'unit_report_%',
        ];

        $driver = config('cache.default');
        $store = Cache::getStore();
        $prefix = method_exists($store, 'getPrefix') ? $store->getPrefix() : (config('cache.prefix') ?? '');

        if ($driver === 'database') {
            $table = config('cache.stores.database.table', 'cache');
            foreach ($patterns as $pattern) {
                // LIKE pattern + optional unit suffix
                $like = $pattern;
                if ($unitId !== null) {
                    $like .= '_'.$unitId; // contoh: monthly_report_%_5
                }
                // Prefix + pattern
                $likeWithPrefix = $prefix ? $prefix.$like : $like;
                DB::table($table)->where('key', 'like', $likeWithPrefix)->delete();
            }

            return true;
        }

        // Untuk store selain database:
        // - Jika superadmin, flush semua (aman sesuai peran)
        // - Jika bukan superadmin, tidak didukung (hindari flush global)
        if ($isSuper) {
            Cache::flush();

            return true;
        }

        return false; // non-database store tidak mendukung hapus parsial tanpa enumerasi key
    }
}
