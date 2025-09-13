<?php

namespace App\Http\Controllers;

use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics and charts
     */
    public function index(Request $request)
    {
        // Debug logging untuk memastikan method dipanggil
        \Log::info('DashboardController index method called', [
            'request_url' => request()->url(),
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
        ]);

        $user = Auth::guard('web')->user();

        // UnitScope akan otomatis memfilter berdasarkan unit user
        $logsQuery = LogPenyimpananLimbah::query();
        $usersQuery = PenggunaSistem::query();

        $totalLogs = $logsQuery->count();
        $totalWasteTypes = JenisLimbah::count();
        $totalStoredLogs = LogPenyimpananLimbah::where('status_log', 'Tersimpan')->count();
        $totalTransported = LogPenyimpananLimbah::where('status_log', 'Diangkut')->count();
        $totalUsers = $usersQuery->count();

        // Get warning days from settings (default 30 if not set)
        $warningDays = DB::table('app_settings')
            ->where('key', 'warning_days')
            ->value('value') ?? 30;

        // Total limbah yang akan kadaluarsa dalam warning days
        // Menggunakan method getDaysUntilExpiry() untuk perhitungan yang konsisten
        $storedWaste = LogPenyimpananLimbah::where('status_log', 'Tersimpan')->get();
        $totalNearExpiry = $storedWaste->filter(function ($waste) use ($warningDays) {
            $daysUntilExpiry = $waste->getDaysUntilExpiry();

            return $daysUntilExpiry !== null && $daysUntilExpiry > 0 && $daysUntilExpiry <= $warningDays;
        })->count();

        // Status distribution - UnitScope akan otomatis memfilter
        $statusDistribution = LogPenyimpananLimbah::select('status_log', DB::raw('count(*) as total'))
            ->groupBy('status_log')
            ->get();

        // Waste storage by month (last 12 months) - UnitScope akan otomatis memfilter
        $monthlyData = LogPenyimpananLimbah::select(
            DB::raw('strftime("%Y", tanggal_limbah_masuk) as year'),
            DB::raw('strftime("%m", tanggal_limbah_masuk) as month'),
            DB::raw('SUM(jumlah_limbah_masuk) as total_waste'),
            DB::raw('COUNT(*) as total_logs')
        )
            ->where('tanggal_limbah_masuk', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Top waste types by quantity - UnitScope akan otomatis memfilter
        $topWasteTypes = LogPenyimpananLimbah::select(
            'jenis_limbah.nama_limbah',
            DB::raw('SUM(jumlah_limbah_masuk) as total_quantity'),
            DB::raw('COUNT(*) as total_logs')
        )
            ->join('jenis_limbah', 'log_penyimpanan_limbah.kode_limbah', '=', 'jenis_limbah.kode_limbah')
            ->groupBy('jenis_limbah.kode_limbah', 'jenis_limbah.nama_limbah')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        // Debug logging untuk troubleshoot masalah setelah navigasi
        \Log::info('Dashboard topWasteTypes Debug', [
            'count' => $topWasteTypes->count(),
            'data' => $topWasteTypes->toArray(),
            'user_id' => auth()->id(),
            'user_unit_id' => auth()->user() ? auth()->user()->unit_id : null,
            'request_url' => request()->url(),
        ]);

        // Top companies by waste quantity - UnitScope akan otomatis memfilter
        $topCompanies = LogPenyimpananLimbah::select(
            'perusahaan_penghasil.nama_perusahaan',
            DB::raw('SUM(jumlah_limbah_masuk) as total_quantity'),
            DB::raw('COUNT(*) as total_logs')
        )
            ->join('perusahaan_penghasil', 'log_penyimpanan_limbah.perusahaan_id', '=', 'perusahaan_penghasil.perusahaan_id')
            ->whereNotNull('log_penyimpanan_limbah.perusahaan_id')
            ->groupBy('perusahaan_penghasil.perusahaan_id', 'perusahaan_penghasil.nama_perusahaan')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        // Waste by branch
        $wasteByBranch = LogPenyimpananLimbah::select(
            'unit_pembangkit.nama_unit',
            DB::raw('SUM(jumlah_limbah_masuk) as total_quantity'),
            DB::raw('COUNT(*) as total_logs')
        )
            ->join('unit_pembangkit', 'log_penyimpanan_limbah.unit_id', '=', 'unit_pembangkit.unit_id')
            ->groupBy('unit_pembangkit.unit_id', 'unit_pembangkit.nama_unit')
            ->orderBy('total_quantity', 'desc')
            ->get();

        // Expired or near expiry waste - UnitScope akan otomatis memfilter
        // Menggunakan method getDaysUntilExpiry() untuk perhitungan yang konsisten
        $allStoredWaste = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit'])
            ->where('status_log', 'Tersimpan')
            ->get();

        $nearExpiryWaste = $allStoredWaste->filter(function ($waste) use ($warningDays) {
            $daysUntilExpiry = $waste->getDaysUntilExpiry();

            return $daysUntilExpiry !== null && $daysUntilExpiry > 0 && $daysUntilExpiry <= $warningDays;
        })->sortBy(function ($waste) {
            return $waste->getDaysUntilExpiry();
        })->take(10);

        // Recent activities - UnitScope akan otomatis memfilter
        // Filter aktivitas dalam 24 jam terakhir
        $recentActivities = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'penggunaSistem'])
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // Debug: Add count for debugging
        $recentActivitiesCount = $recentActivities->count();

        return view('dashboard', compact(
            'totalLogs',
            'totalWasteTypes',
            'totalStoredLogs',
            'totalTransported',
            'totalUsers',
            'totalNearExpiry',
            'statusDistribution',
            'monthlyData',
            'topWasteTypes',
            'topCompanies',
            'wasteByBranch',
            'nearExpiryWaste',
            'recentActivities',
            'recentActivitiesCount',
            'warningDays'
        ) + [
            'wasteTypes' => $totalWasteTypes,
            'companies' => $totalStoredLogs,
        ]);
    }

    /**
     * Get chart data for AJAX requests
     */
    public function getChartData(Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'monthly':
                return $this->getMonthlyChartData();
            case 'status':
                return $this->getStatusChartData();
            case 'waste-types':
                return $this->getWasteTypesChartData();
            case 'companies':
                return $this->getCompaniesChartData();
            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }

    private function getMonthlyChartData()
    {
        // UnitScope akan otomatis memfilter berdasarkan unit user
        $data = LogPenyimpananLimbah::select(
            DB::raw('strftime("%Y", tanggal_limbah_masuk) as year'),
            DB::raw('strftime("%m", tanggal_limbah_masuk) as month'),
            DB::raw('SUM(jumlah_limbah_masuk) as total_waste')
        )
            ->where('tanggal_limbah_masuk', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
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
        $warningDays = DB::table('app_settings')
            ->where('key', 'warning_days')
            ->value('value') ?? 30;

        $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit'])
            ->where('status_log', 'Tersimpan')
            ->where('maksimal_penyimpanan_tanggal', '<=', Carbon::now()->addDays($warningDays))
            ->where('maksimal_penyimpanan_tanggal', '>', Carbon::now());

        // Add calculated field for days remaining (SQLite compatible)
        $query->selectRaw('*, CAST((julianday(maksimal_penyimpanan_tanggal) - julianday("now")) AS INTEGER) as days_remaining');

        if ($sortBy === 'days_remaining') {
            $query->orderByRaw('CAST((julianday(maksimal_penyimpanan_tanggal) - julianday("now")) AS INTEGER) '.$sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $nearExpiryWaste = $query->paginate(20);

        return view('dashboard.near-expiry', compact('nearExpiryWaste', 'sortBy', 'sortOrder'));
    }
}
