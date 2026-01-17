<?php

namespace App\Http\Controllers;

use App\Events\WasteDocumentUploaded;
use App\Helpers\K3Logger;
use App\Models\ApprovalLog;
use App\Models\JenisLimbah;
use App\Models\KategoriKegiatanSumber;
use App\Models\LogPenyimpananLimbah;
use App\Models\PerusahaanPenghasil;
use App\Models\UnitPembangkit;
use App\Models\ApplicationSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class LogPenyimpananLimbahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // UnitScope akan otomatis memfilter berdasarkan unit user
        $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unit', 'penggunaSistem']);

        // Secara default tidak menampilkan log dengan status 'Diangkut' kecuali filter status aktif
        if (!$request->filled('search_status')) {
            $query->whereRaw("LOWER(status_log) != 'diangkut'");
        }

        // Unified search - searches across all relevant fields
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                // Search kode identitas
                $q->where('kode_identitas', 'LIKE', '%' . $searchTerm . '%')
                    // Search uraian pekerjaan
                    ->orWhere('uraian_pekerjaan', 'LIKE', '%' . $searchTerm . '%')
                    // Search status
                    ->orWhere('status_log', 'LIKE', '%' . $searchTerm . '%')
                    // Search jenis limbah (nama or kode)
                    ->orWhereHas('jenisLimbah', function ($jl) use ($searchTerm) {
                        $jl->where('nama_limbah', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('kode_limbah', 'LIKE', '%' . $searchTerm . '%');
                    })
                    // Search perusahaan penghasil
                    ->orWhereHas('perusahaanPenghasil', function ($pp) use ($searchTerm) {
                        $pp->where('nama_perusahaan', 'LIKE', '%' . $searchTerm . '%');
                    })
                    // Search penginput data (nama/email)
                    ->orWhereHas('penggunaSistem', function ($ps) use ($searchTerm) {
                        $ps->where('nama_lengkap', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('email_address', 'LIKE', '%' . $searchTerm . '%');
                    })
                    // Search Unit Name
                    ->orWhereHas('unit', function ($u) use ($searchTerm) {
                        $u->where('nama_unit', 'LIKE', '%' . $searchTerm . '%');
                    });
            });
        }

        // Super Admin Filter: Unit Pembangkit
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();
        $unitPembangkit = [];

        if ($isSuperAdmin) {
            $unitPembangkit = UnitPembangkit::orderBy('nama_unit')->get();

            if ($request->filled('search_unit_id')) {
                $query->where('unit_id', $request->search_unit_id);
            }
        }

        // Optional status filter (for quick tabs)
        if ($request->filled('search_status')) {
            $query->where('status_log', $request->search_status);
        }

        // Urutan default: tanggal limbah masuk terbaru terlebih dahulu
        $logs = $query->orderBy('tanggal_limbah_masuk', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('log-penyimpanan.index', compact('logs', 'unitPembangkit', 'isSuperAdmin'));
    }
    /**
     * Export filtered listing to PDF or Excel.
     */
    public function export(Request $request, string $format)
    {
        // Bangun query sama seperti index
        $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit', 'penggunaSistem']);

        if (!$request->filled('search_status')) {
            $query->whereRaw("LOWER(status_log) != 'diangkut'");
        }

        if ($request->filled('search_jenis')) {
            $query->whereHas('jenisLimbah', function ($q) use ($request) {
                $q->where('nama_limbah', 'LIKE', '%' . $request->search_jenis . '%')
                    ->orWhere('kode_limbah', 'LIKE', '%' . $request->search_jenis . '%');
            });
        }

        if ($request->filled('search_uraian_pekerjaan')) {
            $query->where('uraian_pekerjaan', 'LIKE', '%' . $request->search_uraian_pekerjaan . '%');
        }

        if ($request->filled('search_perusahaan')) {
            $query->whereHas('perusahaanPenghasil', function ($q) use ($request) {
                $q->where('nama_perusahaan', 'LIKE', '%' . $request->search_perusahaan . '%');
            });
        }

        if ($request->filled('search_status')) {
            $query->where('status_log', $request->search_status);
        }

        if ($request->filled('search_tanggal')) {
            $query->whereDate('tanggal_limbah_masuk', $request->search_tanggal);
        }
        if ($request->filled('search_tanggal_mulai')) {
            $query->whereDate('tanggal_limbah_masuk', '>=', $request->search_tanggal_mulai);
        }
        if ($request->filled('search_tanggal_akhir')) {
            $query->whereDate('tanggal_limbah_masuk', '<=', $request->search_tanggal_akhir);
        }

        if ($request->filled('search_kode_identitas')) {
            $query->where('kode_identitas', 'LIKE', '%' . $request->search_kode_identitas . '%');
        }

        $currentUser = Auth::user();
        /** @var \App\Models\PenggunaSistem|null $currentUser */
        $isSuper = $currentUser && is_callable([$currentUser, 'isSuperAdmin'])
            ? (bool) call_user_func([$currentUser, 'isSuperAdmin'])
            : false;
        if ($isSuper && $request->filled('search_penginput')) {
            $searchPenginput = $request->search_penginput;
            $query->whereHas('penggunaSistem', function ($q) use ($searchPenginput) {
                $q->where('nama_lengkap', 'LIKE', '%' . $searchPenginput . '%')
                    ->orWhere('email_address', 'LIKE', '%' . $searchPenginput . '%');
            });
        }

        if ($request->filled('expiry_days_min') || $request->filled('expiry_days_max')) {
            $coalesceExpr = 'COALESCE(tanggal_kadaluarsa, maksimal_penyimpanan_tanggal)';
            if ($request->filled('expiry_days_min')) {
                $minDays = (int) $request->input('expiry_days_min');
                $query->whereRaw("DATEDIFF($coalesceExpr, CURRENT_DATE) >= ?", [$minDays]);
            }
            if ($request->filled('expiry_days_max')) {
                $maxDays = (int) $request->input('expiry_days_max');
                $query->whereRaw("DATEDIFF($coalesceExpr, CURRENT_DATE) <= ?", [$maxDays]);
            }
        }

        $maxRows = ApplicationSetting::getValue('report.max_export_rows', 10000);
        $logs = $query->orderBy('tanggal_limbah_masuk', 'desc')->limit($maxRows)->get();

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

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $jenisLimbah = JenisLimbah::all();
        $perusahaanPenghasil = PerusahaanPenghasil::all();
        $kategoriKegiatanSumber = KategoriKegiatanSumber::all();

        // UnitScope akan otomatis memfilter unit berdasarkan user
        $unitPembangkit = UnitPembangkit::all();

        // Cek apakah Super Admin perlu pilih unit (jika unit_id = NULL)
        $user = Auth::user();
        $requiresUnitSelection = $user->isSuperAdmin() && empty($user->unit_id);

        return view('log-penyimpanan.create', compact('jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit', 'kategoriKegiatanSumber', 'requiresUnitSelection'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->isSuperAdmin();

        // Dynamic validation for upload
        $maxSize = ApplicationSetting::getValue('upload.max_file_size_kb', 10240);
        $extensions = json_decode(ApplicationSetting::getValue('upload.allowed_extensions', '["pdf","doc","docx","xls","xlsx","jpg","jpeg","png"]'), true);
        $mimes = implode(',', $extensions);

        // Validation rules - Super Admin harus pilih unit_id dari form
        $rules = [
            'tanggal_limbah_masuk' => 'required|date',
            'detail_sumber_limbah' => 'required|string',
            'uraian_pekerjaan' => 'nullable|string|max:1000',
            'jumlah_limbah_masuk' => 'required|numeric|min:0.01',
            'kode_limbah' => 'required|exists:jenis_limbah,kode_limbah',
            'perusahaan_nama' => 'nullable|string|max:255',
            'dokumen_limbah' => 'nullable|file|mimes:' . $mimes . '|max:' . $maxSize,
        ];

        // Super Admin tanpa unit_id harus pilih unit dari form
        if ($isSuperAdmin && empty($user->unit_id)) {
            $rules['unit_id'] = 'required|exists:unit_pembangkit,unit_id';
        }

        $validated = $request->validate($rules);

        // Tentukan unit_id: dari form untuk Super Admin, dari user untuk yang lain
        if ($isSuperAdmin && empty($user->unit_id)) {
            $unitId = $validated['unit_id'];
        } else {
            $unitId = $user->unit_id;
        }

        // Validasi unit_id exists
        if (!UnitPembangkit::where('unit_id', $unitId)->exists()) {
            return back()->withErrors(['unit_id' => 'Unit pembangkit tidak valid.'])->withInput();
        }

        // Get jenis limbah to calculate maximum storage date based on waktu_penyimpanan_hari
        $jenisLimbah = JenisLimbah::where('kode_limbah', $validated['kode_limbah'])->first();
        if (!$jenisLimbah) {
            return back()->withErrors(['kode_limbah' => 'Jenis limbah tidak ditemukan.'])->withInput();
        }

        $tanggalMasuk = Carbon::parse($validated['tanggal_limbah_masuk']);
        $maksimalPenyimpanan = $tanggalMasuk->addDays($jenisLimbah->waktu_penyimpanan_hari);

        // Handle perusahaan_nama - cari atau buat perusahaan baru jika diperlukan
        $perusahaanId = null;
        if (!empty($validated['perusahaan_nama'])) {
            $perusahaan = PerusahaanPenghasil::where('nama_perusahaan', $validated['perusahaan_nama'])->first();
            if (!$perusahaan) {
                $perusahaan = PerusahaanPenghasil::create([
                    'nama_perusahaan' => $validated['perusahaan_nama'],
                    'alamat_perusahaan' => 'Alamat belum diisi',
                    'jenis_perusahaan' => 'Belum ditentukan',
                    'status_aktif' => true,
                ]);
            }
            $perusahaanId = $perusahaan->perusahaan_id;
        }

        $documentMeta = $request->file('dokumen_limbah')
            ? $this->uploadWasteDocument($request->file('dokumen_limbah'))
            : null;

        // Determine Approval Status based on settings
        $approvalRequired = ApplicationSetting::getValue('workflow.approval_required', true);
        $autoApproveOperator = ApplicationSetting::getValue('workflow.auto_approve_operator', false);

        $approvalStatus = 'pending';
        $approvedBy = null;
        $approvedAt = null;

        if (!$approvalRequired) {
            $approvalStatus = 'approved';
            $approvedBy = $user->user_id; // Auto-approved by creator if approval disabled
            $approvedAt = now();
        } elseif ($autoApproveOperator && $user->hasRole('Operator')) {
            $approvalStatus = 'approved';
            $approvedBy = $user->user_id; // Auto-approved for trusted operator
            $approvedAt = now();
        }

        $log = LogPenyimpananLimbah::create(array_merge([
            'tanggal_limbah_masuk' => $validated['tanggal_limbah_masuk'],
            'detail_sumber_limbah' => $validated['detail_sumber_limbah'],
            'uraian_pekerjaan' => $validated['uraian_pekerjaan'],
            'jumlah_limbah_masuk' => $validated['jumlah_limbah_masuk'],
            'maksimal_penyimpanan_tanggal' => $maksimalPenyimpanan,
            'status_log' => 'Tersimpan',
            'approval_status' => $approvalStatus,
            'approved_by' => $approvedBy,
            'approved_at' => $approvedAt,
            'user_id' => Auth::user()->user_id,
            'kode_limbah' => $validated['kode_limbah'],
            'perusahaan_id' => $perusahaanId,
            'unit_id' => $unitId,
        ], $documentMeta['attributes'] ?? []));

        if ($documentMeta) {
            K3Logger::fileOperation('UPLOAD', $documentMeta['original_name'], $documentMeta['attributes']['dokumen_path']);
            WasteDocumentUploaded::dispatch($log, $documentMeta['attributes']['dokumen_path'], $documentMeta['original_name'], $documentMeta['size']);
        }

        return redirect()->route('log-penyimpanan.index')
            ->with('success', 'Log penyimpanan limbah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(LogPenyimpananLimbah $logPenyimpanan, Request $request)
    {
        // Policy akan otomatis mengecek akses
        $this->authorize('view', $logPenyimpanan);

        $logPenyimpanan->load(['jenisLimbah', 'perusahaanPenghasil', 'unit', 'penggunaSistem']);

        return view('log-penyimpanan.show', compact('logPenyimpanan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogPenyimpananLimbah $logPenyimpanan, Request $request)
    {
        $jenisLimbah = JenisLimbah::all();
        $perusahaanPenghasil = PerusahaanPenghasil::all();
        $kategoriKegiatanSumber = KategoriKegiatanSumber::all();

        // Policy akan otomatis mengecek akses
        $this->authorize('update', $logPenyimpanan);

        // UnitScope akan otomatis memfilter unit berdasarkan user
        $unitPembangkit = UnitPembangkit::all();

        return view('log-penyimpanan.edit', compact('logPenyimpanan', 'jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit', 'kategoriKegiatanSumber'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogPenyimpananLimbah $logPenyimpanan)
    {
        // Policy akan otomatis mengecek akses
        $this->authorize('update', $logPenyimpanan);

        // Check workflow setting for editing approved logs
        $allowEditApproved = ApplicationSetting::getValue('workflow.edit_approved_logs', false);
        if ($logPenyimpanan->approval_status === 'approved' && !$allowEditApproved && !$request->user()->isSuperAdmin()) {
            return back()->with('error', 'Log yang sudah disetujui tidak dapat diedit (Pengaturan Workflow).');
        }

        // Dynamic validation for upload
        $maxSize = ApplicationSetting::getValue('upload.max_file_size_kb', 10240);
        $extensions = json_decode(ApplicationSetting::getValue('upload.allowed_extensions', '["pdf","doc","docx","xls","xlsx","jpg","jpeg","png"]'), true);
        $mimes = implode(',', $extensions);

        $validated = $request->validate([
            'tanggal_limbah_masuk' => 'required|date',
            'detail_sumber_limbah' => 'required|string|max:1000',
            'uraian_pekerjaan' => 'nullable|string|max:1000',
            'jumlah_limbah_masuk' => 'required|numeric|min:0.01',
            'kode_limbah' => 'required|exists:jenis_limbah,kode_limbah',
            'perusahaan_nama' => 'nullable|string|max:255',
            'tanggal_pengangkutan' => 'nullable|date',
            'jumlah_diangkut' => 'nullable|numeric|min:0',
            'status_log' => 'required|in:Tersimpan,Diangkut,Kadaluarsa',
            'dokumen_limbah' => 'sometimes|nullable|file|mimes:' . $mimes . '|max:' . $maxSize,
        ]);

        // Unit_id tidak dapat diubah, tetap menggunakan yang sudah ada
        $validated['unit_id'] = $logPenyimpanan->unit_id;

        // Handle perusahaan_nama - cari atau buat perusahaan baru jika diperlukan
        $perusahaanId = null;
        if (!empty($validated['perusahaan_nama'])) {
            $perusahaan = PerusahaanPenghasil::where('nama_perusahaan', $validated['perusahaan_nama'])->first();
            if (!$perusahaan) {
                $perusahaan = PerusahaanPenghasil::create([
                    'nama_perusahaan' => $validated['perusahaan_nama'],
                    'alamat_perusahaan' => 'Alamat belum diisi',
                    'jenis_perusahaan' => 'Belum ditentukan',
                    'status_aktif' => true,
                ]);
            }
            $perusahaanId = $perusahaan->perusahaan_id;
        }
        $validated['perusahaan_id'] = $perusahaanId;

        // Recalculate maximum storage date if waste entry date or type changed
        if ($logPenyimpanan->kode_limbah !== $validated['kode_limbah'] || $logPenyimpanan->tanggal_limbah_masuk !== $validated['tanggal_limbah_masuk']) {
            $jenisLimbah = JenisLimbah::where('kode_limbah', $validated['kode_limbah'])->first();
            if (!$jenisLimbah) {
                return back()->withErrors(['kode_limbah' => 'Jenis limbah tidak ditemukan.'])->withInput();
            }

            $tanggalMasuk = Carbon::parse($validated['tanggal_limbah_masuk']);
            $validated['maksimal_penyimpanan_tanggal'] = $tanggalMasuk->addDays($jenisLimbah->waktu_penyimpanan_hari);
        }

        // Remove perusahaan_nama from validated data before updating
        $documentMeta = null;
        if ($request->hasFile('dokumen_limbah')) {
            $this->deleteWasteDocument($logPenyimpanan);
            $documentMeta = $this->uploadWasteDocument($request->file('dokumen_limbah'));
            $validated = array_merge($validated, $documentMeta['attributes']);
        }

        unset($validated['perusahaan_nama'], $validated['dokumen_limbah']);
        $logPenyimpanan->update($validated);

        if ($documentMeta) {
            K3Logger::fileOperation('UPLOAD', $documentMeta['original_name'], $documentMeta['attributes']['dokumen_path']);
            WasteDocumentUploaded::dispatch($logPenyimpanan->fresh(), $documentMeta['attributes']['dokumen_path'], $documentMeta['original_name'], $documentMeta['size']);
        }

        return redirect()->route('log-penyimpanan.index')
            ->with('success', 'Log penyimpanan limbah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogPenyimpananLimbah $logPenyimpanan, Request $request)
    {
        // Policy akan otomatis mengecek akses
        $this->authorize('delete', $logPenyimpanan);

        // Check workflow setting for deleting approved logs
        $allowDeleteApproved = ApplicationSetting::getValue('workflow.delete_approved_logs', false);
        if ($logPenyimpanan->approval_status === 'approved' && !$allowDeleteApproved && !$request->user()->isSuperAdmin()) {
            return back()->with('error', 'Log yang sudah disetujui tidak dapat dihapus (Pengaturan Workflow).');
        }

        $this->deleteWasteDocument($logPenyimpanan);

        $logPenyimpanan->delete();

        return redirect()->route('log-penyimpanan.index')
            ->with('success', 'Log penyimpanan limbah berhasil dihapus.');
    }

    /**
     * Upload waste document to storage and prepare model attributes.
     */
    private function uploadWasteDocument(UploadedFile $file): array
    {
        $directory = 'limbah-documents/' . now()->format('Y/m');
        $path = $file->store($directory, 'public');

        return [
            'attributes' => [
                'dokumen_path' => $path,
                'dokumen_original_name' => $file->getClientOriginalName(),
                'dokumen_mime' => $file->getClientMimeType(),
                'dokumen_size' => (int) $file->getSize(),
                'dokumen_uploaded_at' => now(),
            ],
            'original_name' => $file->getClientOriginalName(),
            'size' => (int) $file->getSize(),
        ];
    }

    /**
     * Delete waste document from storage if present.
     */
    private function deleteWasteDocument(LogPenyimpananLimbah $log): void
    {
        if (!$log->dokumen_path) {
            return;
        }

        if (Storage::disk('public')->exists($log->dokumen_path)) {
            Storage::disk('public')->delete($log->dokumen_path);
            K3Logger::fileOperation(
                'DELETE',
                $log->dokumen_original_name ?? basename($log->dokumen_path),
                $log->dokumen_path
            );
        }

        $log->fill([
            'dokumen_path' => null,
            'dokumen_original_name' => null,
            'dokumen_mime' => null,
            'dokumen_size' => null,
            'dokumen_uploaded_at' => null,
        ])->saveQuietly();
    }

    /**
     * Mark waste as transported
     */
    public function markTransported(Request $request, LogPenyimpananLimbah $logPenyimpanan)
    {
        // Policy akan otomatis mengecek akses
        $this->authorize('update', $logPenyimpanan);

        $validated = $request->validate([
            'tanggal_pengangkutan' => 'required|date',
            'jumlah_diangkut' => 'required|numeric|min:0.01|max:' . $logPenyimpanan->jumlah_limbah_masuk,
        ]);

        $requireDocument = ApplicationSetting::getValue('upload.require_document_for_transport', true);

        // If require_document is true, ensure log has a document OR request has a new file (handled in update usually, but here markTransported is specific status change).
        // Actually markTransported usually just updates status. If it requires document, we should check if one exists on the model.
        if ($requireDocument && !$logPenyimpanan->dokumen_path) {
            return back()->with('error', 'Wajib melampirkan dokumen bukti (manifest) sebelum mengubah status menjadi Diangkut (Pengaturan Upload).');
        }

        $logPenyimpanan->update([
            'tanggal_pengangkutan' => $validated['tanggal_pengangkutan'],
            'jumlah_diangkut' => $validated['jumlah_diangkut'],
            'status_log' => 'Diangkut',
        ]);

        return redirect()->route('log-penyimpanan.index')
            ->with('success', 'Status limbah berhasil diubah menjadi diangkut.');
    }

    public function approve(LogPenyimpananLimbah $logPenyimpanan, Request $request)
    {
        $user = Auth::user();

        if (!$user->canApproveLogs()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menyetujui log limbah.');
        }

        if ($logPenyimpanan->approval_status === 'approved') {
            return back()->with('error', 'Log limbah sudah disetujui.');
        }

        $validated = $request->validate([
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $logPenyimpanan->update([
            'approval_status' => 'approved',
            'approved_by' => $user->user_id,
            'approved_at' => now(),
        ]);

        ApprovalLog::create([
            'log_id' => $logPenyimpanan->log_id,
            'approved_by' => $user->user_id,
            'action' => 'approve',
            'rejected_reason' => null,
        ]);

        return back()->with('success', 'Log limbah berhasil disetujui.');
    }

    public function reject(LogPenyimpananLimbah $logPenyimpanan, Request $request)
    {
        $user = Auth::user();

        if (!$user->canApproveLogs()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menolak log limbah.');
        }

        if ($logPenyimpanan->approval_status === 'rejected') {
            return back()->with('error', 'Log limbah sudah ditolak.');
        }

        $requireReason = ApplicationSetting::getValue('workflow.require_rejection_reason', true);

        $validated = $request->validate([
            'rejected_reason' => $requireReason ? 'required|string|max:1000' : 'nullable|string|max:1000',
        ]);

        $logPenyimpanan->update([
            'approval_status' => 'rejected',
            'rejected_reason' => $validated['rejected_reason'],
        ]);

        ApprovalLog::create([
            'log_id' => $logPenyimpanan->log_id,
            'approved_by' => $user->user_id,
            'action' => 'reject',
            'rejected_reason' => $validated['rejected_reason'],
        ]);

        return back()->with('success', 'Log limbah berhasil ditolak.');
    }
}
