<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApplicationSetting;
use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Return dashboard summary metrics for mobile apps.
     */
    public function summary(Request $request)
    {
        /** @var PenggunaSistem|null $user */
        $user = Auth::guard('sanctum')->user();

        $query = LogPenyimpananLimbah::query();

        // Non-admin users are limited to their unit
        if ($user && !$user->isAdmin()) {
            $query->where('unit_id', $user->unit_id);
        } elseif ($request->filled('unit_id')) {
            // Admin can filter specific unit
            $query->where('unit_id', (int) $request->input('unit_id'));
        }

        $now = Carbon::now();
        $warningDays = (int) ApplicationSetting::getValue('warning_days', 30);
        $nearExpiryThreshold = $now->copy()->addDays($warningDays);

        // Optional date filter (by tanggal_limbah_masuk)
        $from = $request->query('from');
        $to = $request->query('to');
        if ($from || $to) {
            try {
                $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
                $toDate = $to ? Carbon::parse($to)->endOfDay() : null;
                if ($fromDate && $toDate) {
                    $query->whereBetween('tanggal_limbah_masuk', [$fromDate, $toDate]);
                } elseif ($fromDate) {
                    $query->where('tanggal_limbah_masuk', '>=', $fromDate);
                } elseif ($toDate) {
                    $query->where('tanggal_limbah_masuk', '<=', $toDate);
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tanggal tidak valid. Gunakan ISO8601 atau YYYY-MM-DD.',
                ], 422);
            }
        }

        $totalLogs = (clone $query)->count();
        $stored = (clone $query)->where('status_log', 'Tersimpan')->count();
        $transported = (clone $query)->where('status_log', 'Diangkut')->count();
        $nearExpiry = (clone $query)
            ->where('status_log', 'Tersimpan')
            ->where('maksimal_penyimpanan_tanggal', '<=', $nearExpiryThreshold)
            ->where('maksimal_penyimpanan_tanggal', '>', $now)
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_logs' => $totalLogs,
                'stored' => $stored,
                'transported' => $transported,
                'near_expiry' => $nearExpiry,
                'warning_days' => $warningDays,
            ],
        ]);
    }
}
