<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalStatus;
use App\Enums\LogStatus;
use App\Helpers\K3Logger;
use App\Models\ApprovalLog;
use App\Models\LogPenyimpananLimbah;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogPenyimpananApprovalController extends Controller
{
    public function approve(Request $request, int $logId): RedirectResponse
    {
        $log = LogPenyimpananLimbah::findOrFail($logId);
        $user = Auth::user();

        if (!$user->canApproveLogs()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menyetujui log.');
        }

        if ($log->status_log !== LogStatus::Tersimpan) {
            return back()->with('error', 'Hanya log dengan status Tersimpan yang dapat disetujui.');
        }

        ApprovalLog::create([
            'log_id' => $log->log_id,
            'approved_by' => $user->user_id,
            'action' => 'approve',
            'status_sebelumnya' => $log->status_log,
            'rejected_reason' => $request->input('catatan'),
        ]);

        $log->update([
            'status_log' => LogStatus::Diangkut,
            'approval_status' => ApprovalStatus::Approved,
            'approved_at' => now(),
        ]);

        K3Logger::info('Log approved', [
            'log_id' => $log->log_id,
            'approved_by' => Auth::id(),
        ]);

        return redirect()->route('log-penyimpanan.index')
            ->with('success', 'Log berhasil disetujui.');
    }

    public function reject(Request $request, int $logId): RedirectResponse
    {
        $log = LogPenyimpananLimbah::findOrFail($logId);
        $user = Auth::user();

        if (!$user->canApproveLogs()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menolak log.');
        }

        ApprovalLog::create([
            'log_id' => $log->log_id,
            'approved_by' => $user->user_id,
            'action' => 'reject',
            'status_sebelumnya' => $log->status_log,
            'rejected_reason' => $request->input('catatan'),
        ]);

        $log->update([
            'status_log' => LogStatus::Kadaluarsa,
            'approval_status' => ApprovalStatus::Rejected,
        ]);

        K3Logger::info('Log rejected', [
            'log_id' => $log->log_id,
            'rejected_by' => Auth::id(),
            'reason' => $request->input('catatan'),
        ]);

        return redirect()->route('log-penyimpanan.index')
            ->with('success', 'Log berhasil ditolak.');
    }

    public function bulkApprove(Request $request): JsonResponse
    {
        $logIds = $request->input('log_ids', []);
        $catatan = $request->input('catatan', '');
        $user = Auth::user();

        if (!$user->canApproveLogs()) {
            return new JsonResponse(['error' => 'Anda tidak memiliki izin untuk menyetujui log.'], 403);
        }

        if (empty($logIds)) {
            return new JsonResponse(['error' => 'Tidak ada log yang dipilih.'], 400);
        }

        $logs = LogPenyimpananLimbah::whereIn('log_id', $logIds)
            ->where('status_log', LogStatus::Tersimpan)
            ->get();

        foreach ($logs as $log) {
            ApprovalLog::create([
                'log_id' => $log->log_id,
                'approved_by' => $user->user_id,
                'action' => 'approve',
                'status_sebelumnya' => $log->status_log,
                'rejected_reason' => $catatan,
            ]);

            $log->update([
                'status_log' => LogStatus::Diangkut,
                'approval_status' => ApprovalStatus::Approved,
                'approved_at' => now(),
            ]);

            K3Logger::info('Log bulk approved', [
                'log_id' => $log->log_id,
                'approved_by' => Auth::id(),
            ]);
        }

        return new JsonResponse([
            'message' => count($logs) . ' log berhasil disetujui.'
        ]);
    }
}
