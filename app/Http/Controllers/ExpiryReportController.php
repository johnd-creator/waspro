<?php

namespace App\Http\Controllers;

use App\Exports\ExpiryReportExport;
use App\Models\JenisLimbah;
use App\Models\LogPenyimpananLimbah;
use App\Models\PerusahaanPenghasil;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExpiryReportController extends Controller
{
    public function index(Request $request)
    {
        $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit'])
            ->where('status_log', 'Tersimpan');

        // Tampilkan data yang akan kadaluarsa dalam 30 hari ke depan
        if (! $request->filled('expiry_status') && ! $request->filled('date_from') && ! $request->filled('date_to')) {
            $today = Carbon::now();
            $thirtyDaysLater = Carbon::now()->addDays(30);
            $query->where(function ($q) use ($today, $thirtyDaysLater) {
                $q->whereDate('tanggal_kadaluarsa', '>=', $today)
                    ->whereDate('tanggal_kadaluarsa', '<=', $thirtyDaysLater);
            });
        } else {
            // Filter by expiry status
            if ($request->filled('expiry_status')) {
                $query->where('expiry_status', $request->expiry_status);
            }

            // Filter by date range (tanggal masuk limbah)
            if ($request->filled('date_from')) {
                $query->whereDate('tanggal_limbah_masuk', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('tanggal_limbah_masuk', '<=', $request->date_to);
            }
        }

        // Filter by jenis limbah
        if ($request->filled('jenis_limbah_id')) {
            $query->where('jenis_limbah_id', $request->jenis_limbah_id);
        }

        // Filter by perusahaan
        if ($request->filled('perusahaan_id')) {
            $query->where('perusahaan_id', $request->perusahaan_id);
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
            $query->where('expiry_status', $request->expiry_status);
        }

        if ($request->filled('jenis_limbah_id')) {
            $query->where('jenis_limbah_id', $request->jenis_limbah_id);
        }

        if ($request->filled('perusahaan_id')) {
            $query->where('perusahaan_id', $request->perusahaan_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_limbah_masuk', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_limbah_masuk', '<=', $request->date_to);
        }

        $query->orderBy('tanggal_kadaluarsa', 'asc');
        $logs = $query->get();

        $format = $request->get('format', 'excel');

        if ($format === 'pdf') {
            $data = [
                'logs' => $logs,
                'expiryStatus' => $request->get('expiry_status'),
                'dateFrom' => $request->get('date_from'),
                'dateTo' => $request->get('date_to'),
                'jenisLimbahId' => $request->get('jenis_limbah_id'),
                'perusahaanId' => $request->get('perusahaan_id'),
            ];

            $pdf = Pdf::loadView('expiry-reports-pdf', $data);

            return $pdf->download('laporan-expiry-limbah-'.Carbon::now()->format('Y-m-d-H-i-s').'.pdf');
        }

        $filename = 'laporan-expiry-limbah-'.Carbon::now()->format('Y-m-d-H-i-s').'.xlsx';

        return Excel::download(new ExpiryReportExport($logs), $filename);
    }

    private function getExpiryStatistics(Request $request)
    {
        $query = LogPenyimpananLimbah::where('status_log', 'Tersimpan');

        // Apply filters if provided
        if ($request) {
            if ($request->filled('jenis_limbah_id')) {
                $query->where('jenis_limbah_id', $request->jenis_limbah_id);
            }
            if ($request->filled('perusahaan_id')) {
                $query->where('perusahaan_id', $request->perusahaan_id);
            }
            if ($request->filled('date_from')) {
                $query->where('tanggal_kadaluarsa', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->where('tanggal_kadaluarsa', '<=', $request->date_to);
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
}
