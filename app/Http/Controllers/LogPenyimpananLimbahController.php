<?php

namespace App\Http\Controllers;

use App\Events\WasteDocumentUploaded;
use App\Helpers\K3Logger;
use App\Models\JenisLimbah;
use App\Models\KategoriKegiatanSumber;
use App\Models\LogPenyimpananLimbah;
use App\Models\PerusahaanPenghasil;
use App\Models\UnitPembangkit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LogPenyimpananLimbahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // UnitScope akan otomatis memfilter berdasarkan unit user
        $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unit', 'penggunaSistem']);

        // Filter untuk tidak menampilkan log dengan status 'diangkut'
        $query->whereRaw("LOWER(status_log) != 'diangkut'");

        // Search filters
        if ($request->filled('search_jenis')) {
            $query->whereHas('jenisLimbah', function ($q) use ($request) {
                $q->where('nama_limbah', 'LIKE', '%'.$request->search_jenis.'%')
                    ->orWhere('kode_limbah', 'LIKE', '%'.$request->search_jenis.'%');
            });
        }

        if ($request->filled('search_perusahaan')) {
            $query->whereHas('perusahaanPenghasil', function ($q) use ($request) {
                $q->where('nama_perusahaan', 'LIKE', '%'.$request->search_perusahaan.'%');
            });
        }

        if ($request->filled('search_status')) {
            $query->where('status_log', $request->search_status);
        }

        if ($request->filled('search_tanggal')) {
            $query->whereDate('tanggal_limbah_masuk', $request->search_tanggal);
        }

        if ($request->filled('search_kode_identitas')) {
            $query->where('kode_identitas', 'LIKE', '%'.$request->search_kode_identitas.'%');
        }

        $logs = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString(); // Preserve search parameters in pagination

        return view('log-penyimpanan.index', compact('logs'));
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

        return view('log-penyimpanan.create', compact('jenisLimbah', 'perusahaanPenghasil', 'unitPembangkit', 'kategoriKegiatanSumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_limbah_masuk' => 'required|date',
            'detail_sumber_limbah' => 'required|string',
            'jumlah_limbah_masuk' => 'required|numeric|min:0.01',
            'kode_limbah' => 'required|exists:jenis_limbah,kode_limbah',
            'perusahaan_nama' => 'nullable|string|max:255',
            'dokumen_limbah' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:'.config('app.max_upload_size', 10240),
        ]);

        // Gunakan unit_id dari user yang login
        $unitId = Auth::user()->unit_id;

        // Validasi unit_id exists
        if (! UnitPembangkit::where('unit_id', $unitId)->exists()) {
            return back()->withErrors(['unit_id' => 'Unit pembangkit tidak valid.'])->withInput();
        }

        // Get jenis limbah to calculate maximum storage date based on waktu_penyimpanan_hari
        $jenisLimbah = JenisLimbah::where('kode_limbah', $validated['kode_limbah'])->first();
        if (! $jenisLimbah) {
            return back()->withErrors(['kode_limbah' => 'Jenis limbah tidak ditemukan.'])->withInput();
        }

        $tanggalMasuk = Carbon::parse($validated['tanggal_limbah_masuk']);
        $maksimalPenyimpanan = $tanggalMasuk->addDays($jenisLimbah->waktu_penyimpanan_hari);

        // Handle perusahaan_nama - cari atau buat perusahaan baru jika diperlukan
        $perusahaanId = null;
        if (! empty($validated['perusahaan_nama'])) {
            $perusahaan = PerusahaanPenghasil::where('nama_perusahaan', $validated['perusahaan_nama'])->first();
            if (! $perusahaan) {
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

        $log = LogPenyimpananLimbah::create(array_merge([
            'tanggal_limbah_masuk' => $validated['tanggal_limbah_masuk'],
            'detail_sumber_limbah' => $validated['detail_sumber_limbah'],
            'jumlah_limbah_masuk' => $validated['jumlah_limbah_masuk'],
            'maksimal_penyimpanan_tanggal' => $maksimalPenyimpanan,
            'status_log' => 'Tersimpan',
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

        $validated = $request->validate([
            'tanggal_limbah_masuk' => 'required|date',
            'detail_sumber_limbah' => 'required|string|max:1000',
            'jumlah_limbah_masuk' => 'required|numeric|min:0.01',
            'kode_limbah' => 'required|exists:jenis_limbah,kode_limbah',
            'perusahaan_nama' => 'nullable|string|max:255',
            'tanggal_pengangkutan' => 'nullable|date',
            'jumlah_diangkut' => 'nullable|numeric|min:0',
            'status_log' => 'required|in:Tersimpan,Diangkut,Kadaluarsa',
            'dokumen_limbah' => 'sometimes|nullable|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg|max:'.config('app.max_upload_size', 10240),
        ]);

        // Unit_id tidak dapat diubah, tetap menggunakan yang sudah ada
        $validated['unit_id'] = $logPenyimpanan->unit_id;

        // Handle perusahaan_nama - cari atau buat perusahaan baru jika diperlukan
        $perusahaanId = null;
        if (! empty($validated['perusahaan_nama'])) {
            $perusahaan = PerusahaanPenghasil::where('nama_perusahaan', $validated['perusahaan_nama'])->first();
            if (! $perusahaan) {
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
            if (! $jenisLimbah) {
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
        $directory = 'limbah-documents/'.now()->format('Y/m');
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
        if (! $log->dokumen_path) {
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
            'jumlah_diangkut' => 'required|numeric|min:0.01|max:'.$logPenyimpanan->jumlah_limbah_masuk,
        ]);

        $logPenyimpanan->update([
            'tanggal_pengangkutan' => $validated['tanggal_pengangkutan'],
            'jumlah_diangkut' => $validated['jumlah_diangkut'],
            'status_log' => 'Diangkut',
        ]);

        return redirect()->route('log-penyimpanan.index')
            ->with('success', 'Status limbah berhasil diubah menjadi diangkut.');
    }
}
