<?php

namespace App\Http\Controllers;

use App\Events\WasteDocumentUploaded;
use App\Helpers\K3Logger;
use App\Http\Requests\StoreLogPenyimpananRequest;
use App\Http\Requests\UpdateLogPenyimpananRequest;
use App\Models\ApprovalLog;
use App\Models\JenisLimbah;
use App\Models\KategoriKegiatanSumber;
use App\Models\LogPenyimpananLimbah;
use App\Models\PerusahaanPenghasil;
use App\Models\UnitPembangkit;
use App\Services\LogPenyimpananService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;

class LogPenyimpananLimbahController extends Controller
{
    protected LogPenyimpananService $logService;

    public function __construct(LogPenyimpananService $logService)
    {
        $this->logService = $logService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'search_unit_id', 'search_status']);
        $logs = $this->logService->getFilteredLogs($filters);

        $user = \Illuminate\Support\Facades\Auth::user();
        $isSuperAdmin = method_exists($user, 'isSuperAdmin') ? $user->isSuperAdmin() : false;
        $unitPembangkit = [];

        if ($isSuperAdmin) {
            $unitPembangkit = UnitPembangkit::orderBy('nama_unit')->get();
        }

        return view('log-penyimpanan.index', compact('logs', 'unitPembangkit', 'isSuperAdmin'));
    }

    public function export(Request $request, string $format)
    {
        $filters = $request->only([
            'search',
            'search_jenis',
            'search_uraian_pekerjaan',
            'search_perusahaan',
            'search_status',
            'search_tanggal',
            'search_tanggal_mulai',
            'search_tanggal_akhir',
            'search_kode_identitas',
            'search_penginput',
            'expiry_days_min',
            'expiry_days_max'
        ]);

        $logs = $this->logService->getFilteredLogsForExport($filters, false);

        if ($format === 'excel') {
            $filename = 'log-penyimpanan-' . now()->format('Y-m-d-H-i-s') . '.xlsx';
            return Excel::download(new \App\Exports\LogIndexExport(['logs' => $logs]), $filename);
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('log-penyimpanan.export-pdf', [
                'logs' => $logs,
                'generatedAt' => now(),
            ]);

            return $pdf->download('log-penyimpanan-' . now()->format('Y-m-d-H-i-s') . '.pdf');
        }

        abort(404);
    }

    public function create(Request $request)
    {
        $jenisLimbah = JenisLimbah::all();
        $perusahaanPenghasil = PerusahaanPenghasil::all();
        $kategoriKegiatanSumber = KategoriKegiatanSumber::all();
        $unitPembangkit = UnitPembangkit::all();

        $user = \Illuminate\Support\Facades\Auth::user();
        $requiresUnitSelection = method_exists($user, 'isSuperAdmin') ? ($user->isSuperAdmin() && empty($user->unit_id)) : false;

        return view('log-penyimpanan.create', compact(
            'jenisLimbah',
            'perusahaanPenghasil',
            'kategoriKegiatanSumber',
            'unitPembangkit',
            'requiresUnitSelection'
        ));
    }

    public function store(StoreLogPenyimpananRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();

        $documentMeta = null;
        if ($request->hasFile('dokumen_limbah')) {
            $documentMeta = $this->logService->uploadDocument($request->file('dokumen_limbah'));
            $validated['dokumen_path'] = $documentMeta['path'];
        }

        $unitId = null;
        if ($user->isSuperAdmin() && empty($user->unit_id)) {
            $unitId = $validated['unit_id'];
        } else {
            $unitId = $user->unit_id;
        }

        $log = $this->logService->createLog($validated, $user->user_id, $unitId);

        if ($documentMeta) {
            WasteDocumentUploaded::dispatch(
                $log,
                $documentMeta['path'],
                $documentMeta['original_name'],
                $documentMeta['size']
            );
        }

        return redirect()->route('log-penyimpanan.index')
            ->with('success', 'Log penyimpanan limbah berhasil ditambahkan.');
    }

    public function show(LogPenyimpananLimbah $logPenyimpanan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $logPenyimpanan->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat log ini.');
        }

        $logPenyimpanan->load([
            'jenisLimbah',
            'perusahaanPenghasil',
            'unit',
            'penggunaSistem',
            'approvalLogs.peranPengguna',
            'auditLogs',
        ]);

        return view('log-penyimpanan.show', compact('logPenyimpanan'));
    }

    public function edit(Request $request, LogPenyimpananLimbah $logPenyimpanan)
    {
        $user = Auth::user();

        if ($logPenyimpanan->status_log === 'Diangkut') {
            return back()->with('error', 'Log yang sudah diangkut tidak dapat diedit.');
        }

        $canEditLogs = \App\Models\ApplicationSetting::getValue('workflow.can_edit_logs', true);

        if (!$canEditLogs && $logPenyimpanan->status_log === 'Tersimpan') {
            $userRole = $user->peranPengguna->first()->nama_peran ?? 'User';
            $canApprove = in_array($userRole, ['Super Admin', 'Administrator']);

            if (!$canApprove) {
                return back()->with('error', 'Log yang sudah disetujui tidak dapat diedit (Pengaturan Workflow).');
            }
        }

        if (!$user->isSuperAdmin() && $logPenyimpanan->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit log ini.');
        }

        $jenisLimbah = JenisLimbah::all();
        $perusahaanPenghasil = PerusahaanPenghasil::all();
        $kategoriKegiatanSumber = KategoriKegiatanSumber::all();
        $unitPembangkit = UnitPembangkit::all();
        $requiresUnitSelection = $user->isSuperAdmin() && empty($user->unit_id);

        return view('log-penyimpanan.edit', compact(
            'logPenyimpanan',
            'jenisLimbah',
            'perusahaanPenghasil',
            'kategoriKegiatanSumber',
            'unitPembangkit',
            'requiresUnitSelection'
        ));
    }

    public function update(UpdateLogPenyimpananRequest $request, LogPenyimpananLimbah $logPenyimpanan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $logPenyimpanan->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit log ini.');
        }

        $canEditLogs = \App\Models\ApplicationSetting::getValue('workflow.can_edit_logs', true);

        if (!$canEditLogs && $logPenyimpanan->status_log !== 'Kadaluarsa') {
            return back()->with('error', 'Log yang sudah disetujui tidak dapat diedit (Pengaturan Workflow).');
        }

        $validated = $request->validated();

        if ($request->hasFile('dokumen_limbah')) {
            if ($logPenyimpanan->dokumen_path) {
                Storage::disk('local')->delete($logPenyimpanan->dokumen_path);
            }
            $documentMeta = $this->logService->uploadDocument($request->file('dokumen_limbah'));
            $validated['dokumen_path'] = $documentMeta['path'];
        }

        if ($user->isSuperAdmin() && empty($user->unit_id)) {
            $validated['unit_id'] = $request->input('unit_id');
        } else {
            $validated['unit_id'] = $user->unit_id;
        }

        $updated = $this->logService->updateLog($logPenyimpanan, $validated);

        if (!$updated) {
            return back()->with('error', 'Log ini tidak dapat diperbarui.');
        }

        $logPenyimpanan->fresh()->load(['jenisLimbah', 'perusahaanPenghasil', 'unit']);

        if ($logPenyimpanan->dokumen_path) {
            WasteDocumentUploaded::dispatch(
                $logPenyimpanan,
                $logPenyimpanan->dokumen_path,
                pathinfo($logPenyimpanan->dokumen_path, PATHINFO_BASENAME),
                Storage::disk('local')->size($logPenyimpanan->dokumen_path)
            );
        }

        K3Logger::databaseOperation('UPDATE', 'log_penyimpanan_limbah', [
            'log_id' => $logPenyimpanan->log_id,
            'updated_by' => auth()->id(),
            'changes' => array_keys($validated),
        ], $logPenyimpanan->log_id);

        return redirect()->route('log-penyimpanan.index')
            ->with('success', 'Log penyimpanan limbah berhasil diperbarui.');
    }

    public function destroy(LogPenyimpananLimbah $logPenyimpanan)
    {
        $user = Auth::user();

        if (!$user->isSuperAdmin() && $logPenyimpanan->unit_id !== $user->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus log ini.');
        }

        if ($logPenyimpanan->status_log === 'Diangkut') {
            return back()->with('error', 'Log yang sudah diangkut tidak dapat dihapus.');
        }

        if ($logPenyimpanan->dokumen_path) {
            Storage::disk('local')->delete($logPenyimpanan->dokumen_path);
        }

        $logPenyimpanan->delete();

        K3Logger::databaseOperation('DELETE', 'log_penyimpanan_limbah', [
            'log_id' => $logPenyimpanan->log_id,
            'deleted_by' => auth()->id(),
        ], $logPenyimpanan->log_id);

        return redirect()->route('log-penyimpanan.index')
            ->with('success', 'Log penyimpanan limbah berhasil dihapus.');
    }

    public function getJenisLimbahData($jenisId)
    {
        $jenis = JenisLimbah::find($jenisId);

        if (!$jenis) {
            return new JsonResponse(['error' => 'Jenis limbah tidak ditemukan'], 404);
        }

        $expiryDate = $this->logService->calculateExpiryDate($jenis->kode_limbah, now());

        return new JsonResponse([
            'waktu_penyimpanan_hari' => $jenis->waktu_penyimpanan_hari,
            'expiry_date' => $expiryDate ? $expiryDate->format('Y-m-d') : null,
            'biaya_pengangkutan_per_kg' => $jenis->biaya_pengangkutan_per_kg,
        ]);
    }

    public function autocompletePerusahaan(Request $request)
    {
        $term = $request->input('term', '');

        if (strlen($term) < 2) {
            return new JsonResponse([]);
        }

        $perusahaan = PerusahaanPenghasil::where('nama_perusahaan', 'LIKE', '%' . $term . '%')
            ->orderBy('nama_perusahaan')
            ->limit(10)
            ->get(['perusahaan_id', 'nama_perusahaan']);

        return new JsonResponse($perusahaan);
    }

    public function getStatusCounts(): JsonResponse
    {
        $user = Auth::user();
        $query = LogPenyimpananLimbah::query();

        if (!$user->isSuperAdmin() && $user->unit_id) {
            $query->where('unit_id', $user->unit_id);
        }

        return new JsonResponse([
            'tersimpan' => $query->where('status_log', 'Tersimpan')->count(),
            'diangkut' => $query->where('status_log', 'Diangkut')->count(),
            'kadaluarsa' => $query->where('status_log', 'Kadaluarsa')->count(),
        ]);
    }

    public function approve(Request $request, $logId)
    {
        $log = LogPenyimpananLimbah::findOrFail($logId);
        $user = Auth::user();

        if (!$user->canApproveLogs()) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menyetujui log.');
        }

        if ($log->status_log !== 'Tersimpan') {
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
            'status_log' => 'Diangkut',
            'approval_status' => 'approved',
            'approved_at' => now(),
        ]);

        K3Logger::info('Log approved', [
            'log_id' => $log->log_id,
            'approved_by' => \Illuminate\Support\Facades\Auth::id(),
        ]);

        return redirect()->route('log-penyimpanan.index')
            ->with('success', 'Log berhasil disetujui.');
    }

    public function reject(Request $request, $logId)
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
            'status_log' => 'Kadaluarsa',
            'approval_status' => 'rejected',
        ]);

        K3Logger::info('Log rejected', [
            'log_id' => $log->log_id,
            'rejected_by' => \Illuminate\Support\Facades\Auth::id(),
            'reason' => $request->input('catatan'),
        ]);

        return redirect()->route('log-penyimpanan.index')
            ->with('success', 'Log berhasil ditolak.');
    }

    public function bulkApprove(Request $request)
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
            ->where('status_log', 'Tersimpan')
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
                'status_log' => 'Diangkut',
                'approval_status' => 'approved',
                'approved_at' => now(),
            ]);

            K3Logger::info('Log bulk approved', [
                'log_id' => $log->log_id,
                'approved_by' => \Illuminate\Support\Facades\Auth::id(),
            ]);
        }

        return new JsonResponse([
            'message' => count($logs) . ' log berhasil disetujui.'
        ]);
    }
}
