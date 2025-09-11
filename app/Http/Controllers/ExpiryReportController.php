<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogPenyimpananLimbah;
use App\Models\JenisLimbah;
use App\Models\PerusahaanPenghasil;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ExpiryReportExport;

class ExpiryReportController extends Controller
{
    public function index(Request $request)
    {
        $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit'])
            ->where('status_log', 'Tersimpan');

        // Filter by expiry status
        if ($request->filled('expiry_status')) {
            $query->byExpiryStatus($request->expiry_status);
        }

        // Filter by jenis limbah
        if ($request->filled('jenis_limbah_id')) {
            $query->where('jenis_limbah_id', $request->jenis_limbah_id);
        }

        // Filter by perusahaan
        if ($request->filled('perusahaan_id')) {
            $query->where('perusahaan_penghasil_id', $request->perusahaan_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('tanggal_kadaluarsa', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('tanggal_kadaluarsa', '<=', $request->date_to);
        }

        // Sort by expiry date
        $query->orderBy('tanggal_kadaluarsa', 'asc');

        $logs = $query->paginate(20)->withQueryString();

        // Get filter options
        $jenisLimbah = JenisLimbah::where('status_aktif', true)->get();
        $perusahaan = PerusahaanPenghasil::all();

        // Get summary statistics
        $summary = $this->getExpiryStatistics($request);

        return view('expiry-reports.index', compact('logs', 'jenisLimbah', 'perusahaan', 'summary'));
    }

    public function export(Request $request)
    {
        $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit'])
            ->where('status_log', 'Tersimpan');

        // Apply same filters as index
        if ($request->filled('expiry_status')) {
            $query->byExpiryStatus($request->expiry_status);
        }
        if ($request->filled('jenis_limbah_id')) {
            $query->where('jenis_limbah_id', $request->jenis_limbah_id);
        }
        if ($request->filled('perusahaan_id')) {
            $query->where('perusahaan_penghasil_id', $request->perusahaan_id);
        }
        if ($request->filled('date_from')) {
            $query->where('tanggal_kadaluarsa', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('tanggal_kadaluarsa', '<=', $request->date_to);
        }

        $query->orderBy('tanggal_kadaluarsa', 'asc');
        $logs = $query->get();

        $filename = 'laporan-expiry-limbah-' . Carbon::now()->format('Y-m-d-H-i-s') . '.xlsx';

        return Excel::download(new ExpiryReportExport($logs), $filename);
    }

    public function dashboard()
    {
        $today = Carbon::today();
        $nextWeek = Carbon::today()->addWeek();
        $nextMonth = Carbon::today()->addMonth();

        // Get counts for dashboard cards
        $expiredCount = LogPenyimpananLimbah::expired()->count();
        $criticalCount = LogPenyimpananLimbah::critical()->count();
        $warningCount = LogPenyimpananLimbah::warning()->count();
        $safeCount = LogPenyimpananLimbah::byExpiryStatus('Safe')->count();

        // Get recent expired items
        $recentExpired = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil'])
            ->expired()
            ->orderBy('tanggal_kadaluarsa', 'desc')
            ->limit(10)
            ->get();

        // Get items expiring soon
        $expiringSoon = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil'])
            ->where('status_log', 'Tersimpan')
            ->where('tanggal_kadaluarsa', '>=', $today)
            ->where('tanggal_kadaluarsa', '<=', $nextWeek)
            ->orderBy('tanggal_kadaluarsa', 'asc')
            ->limit(10)
            ->get();

        // Get chart data for expiry trends
        $chartData = $this->getExpiryChartData();

        return view('expiry-reports.dashboard', compact(
            'expiredCount', 'criticalCount', 'warningCount', 'safeCount',
            'recentExpired', 'expiringSoon', 'chartData'
        ));
    }

    private function getExpiryStatistics($request = null)
    {
        $query = LogPenyimpananLimbah::where('status_log', 'Tersimpan');

        // Apply filters if provided
        if ($request) {
            if ($request->filled('jenis_limbah_id')) {
                $query->where('jenis_limbah_id', $request->jenis_limbah_id);
            }
            if ($request->filled('perusahaan_id')) {
                $query->where('perusahaan_penghasil_id', $request->perusahaan_id);
            }
        }

        return [
            'total' => $query->count(),
            'expired' => (clone $query)->expired()->count(),
            'critical' => (clone $query)->critical()->count(),
            'warning' => (clone $query)->warning()->count(),
            'safe' => (clone $query)->byExpiryStatus('Safe')->count(),
        ];
    }

    private function getExpiryChartData()
    {
        $data = LogPenyimpananLimbah::select(
                DB::raw('DATE(tanggal_kadaluarsa) as date'),
                DB::raw('COUNT(*) as count'),
                'expiry_status'
            )
            ->where('status_log', 'Tersimpan')
            ->whereNotNull('tanggal_kadaluarsa')
            ->where('tanggal_kadaluarsa', '>=', Carbon::now()->subMonth())
            ->where('tanggal_kadaluarsa', '<=', Carbon::now()->addMonth())
            ->groupBy('date', 'expiry_status')
            ->orderBy('date')
            ->get();

        return $data->groupBy('date')->map(function ($items, $date) {
            return [
                'date' => $date,
                'expired' => $items->where('expiry_status', 'Expired')->sum('count'),
                'critical' => $items->where('expiry_status', 'Critical')->sum('count'),
                'warning' => $items->where('expiry_status', 'Warning')->sum('count'),
                'safe' => $items->where('expiry_status', 'Safe')->sum('count'),
            ];
        })->values();
    }
}
