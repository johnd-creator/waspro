<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $user = Auth::guard('web')->user();
        
        $filters = [];
        if ($user && $user->isSuperAdmin()) {
            $filters['unit_id'] = $request->input('unit_id');
        }

        if ($request->input('lite') === '1') {
            $filters['lite_mode'] = true;
        }

        $dashboardData = $this->dashboardService->getDashboardData($filters);

        $wasteByMonth = $dashboardData['charts']['waste_by_month'] ?? [];
        $monthlyChartLabels = collect($wasteByMonth)->pluck('bulan')->toArray();
        $monthlyChartValues = collect($wasteByMonth)->pluck('total_jumlah')->toArray();

        $wasteByType = $dashboardData['charts']['waste_by_type'] ?? [];
        $topWasteTypes = collect($wasteByType)->take(10)->values()->toArray();

        $totalLogs = $dashboardData['statistics']['total_logs'] ?? 0;
        $storedLogs = $dashboardData['statistics']['stored_logs'] ?? 0;
        $transportedLogs = $dashboardData['statistics']['transported_logs'] ?? 0;
        $expiredLogs = $dashboardData['statistics']['expired_logs'] ?? 0;
        $estimatedCost = $dashboardData['statistics']['estimated_cost'] ?? 0;
        $transportedCost = $dashboardData['statistics']['transported_cost'] ?? 0;

        $statusChartLabels = ['Tersimpan', 'Diangkut', 'Kadaluarsa'];
        $statusChartValues = [$storedLogs, $transportedLogs, $expiredLogs];
        $statusChartBackgroundColors = ['rgba(34, 197, 94, 0.7)', 'rgba(59, 130, 246, 0.7)', 'rgba(239, 68, 68, 0.7)'];
        $statusChartBorderColors = ['rgb(34, 197, 94)', 'rgb(59, 130, 246)', 'rgb(239, 68, 68)'];

        $statusSummary = [];
        if ($totalLogs > 0) {
            $statusSummary = [
                [
                    'label' => 'Tersimpan',
                    'count' => $storedLogs,
                    'percentage' => round(($storedLogs / $totalLogs) * 100, 1),
                    'dot_class' => 'bg-emerald-500',
                    'text_class' => 'text-emerald-600',
                ],
                [
                    'label' => 'Diangkut',
                    'count' => $transportedLogs,
                    'percentage' => round(($transportedLogs / $totalLogs) * 100, 1),
                    'dot_class' => 'bg-blue-500',
                    'text_class' => 'text-blue-600',
                ],
                [
                    'label' => 'Kadaluarsa',
                    'count' => $expiredLogs,
                    'percentage' => round(($expiredLogs / $totalLogs) * 100, 1),
                    'dot_class' => 'bg-red-500',
                    'text_class' => 'text-red-600',
                ],
            ];
        }

        $warningDays = 7;
        
        return view('dashboard.index', array_merge($dashboardData, [
            'totalLogs' => $totalLogs,
            'totalStoredLogs' => $storedLogs,
            'totalTransported' => $transportedLogs,
            'totalExpiredLogs' => $expiredLogs,
            'totalEstimatedCost' => $estimatedCost,
            'totalTransportedCost' => $transportedCost,
            'totalNearExpiry' => $dashboardData['statistics']['near_expiry'] ?? 0,
            'totalWasteTypes' => $dashboardData['statistics']['active_waste_types'] ?? 0,
            'totalUsers' => $dashboardData['statistics']['total_users'] ?? 0,
            'wasteByType' => $wasteByType,
            'topWasteTypes' => $topWasteTypes,
            'wasteByMonth' => $wasteByMonth,
            'wasteByCompany' => $dashboardData['charts']['waste_by_company'] ?? [],
            'recentLogs' => $dashboardData['recentLogs'] ?? [],
            'recentActivities' => collect($dashboardData['recentLogs'] ?? []),
            'nearExpiryWaste' => collect($dashboardData['expiryWarnings'] ?? []),
            'warningDays' => $warningDays,
            'monthlyChartLabels' => $monthlyChartLabels,
            'monthlyChartValues' => $monthlyChartValues,
            'statusChartLabels' => $statusChartLabels,
            'statusChartValues' => $statusChartValues,
            'statusChartBackgroundColors' => $statusChartBackgroundColors,
            'statusChartBorderColors' => $statusChartBorderColors,
            'statusSummary' => $statusSummary,
            'units' => $this->dashboardService->getUnits(),
            'selectedUnitId' => $request->input('unit_id'),
            'selectedUnitName' => $request->input('unit_id')
                ? \App\Models\UnitPembangkit::find($request->input('unit_id'))?->nama_unit
                : 'Global',
        ]));
    }

    public function refreshCache(Request $request)
    {
        $user = Auth::guard('web')->user();
        
        $filters = [];
        if ($user && $user->isSuperAdmin()) {
            $filters['unit_id'] = $request->input('unit_id');
        }

        $this->dashboardService->clearCache($filters);

        return redirect()->route('dashboard.index')
            ->with('success', 'Dashboard cache berhasil di-refresh.');
    }

    public function getChartData(Request $request)
    {
        $user = Auth::guard('web')->user();
        
        $filters = [];
        if ($user && $user->isSuperAdmin()) {
            $filters['unit_id'] = $request->input('unit_id');
        }

        $charts = $this->dashboardService->getChartData($filters);

        return response()->json($charts);
    }

    public function getStatistics(Request $request)
    {
        $user = Auth::guard('web')->user();
        
        $filters = [];
        if ($user && $user->isSuperAdmin()) {
            $filters['unit_id'] = $request->input('unit_id');
        }

        $statistics = $this->dashboardService->getStatistics($filters);

        return response()->json($statistics);
    }
}
