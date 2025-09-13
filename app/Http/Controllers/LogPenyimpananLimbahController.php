<?php

namespace App\Http\Controllers;

use App\Models\JenisLimbah;
use App\Models\KategoriKegiatanSumber;
use App\Models\LogPenyimpananLimbah;
use App\Models\PerusahaanPenghasil;
use App\Models\UnitPembangkit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogPenyimpananLimbahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // UnitScope akan otomatis memfilter berdasarkan unit user
        $query = LogPenyimpananLimbah::with(['jenisLimbah', 'perusahaanPenghasil', 'unit', 'penggunaSistem']);

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
            'perusahaan_id' => 'nullable|exists:perusahaan_penghasil,perusahaan_id',
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

        $log = LogPenyimpananLimbah::create([
            'tanggal_limbah_masuk' => $validated['tanggal_limbah_masuk'],
            'detail_sumber_limbah' => $validated['detail_sumber_limbah'],
            'jumlah_limbah_masuk' => $validated['jumlah_limbah_masuk'],
            'maksimal_penyimpanan_tanggal' => $maksimalPenyimpanan,
            'status_log' => 'Tersimpan',
            'user_id' => Auth::user()->user_id,
            'kode_limbah' => $validated['kode_limbah'],
            'perusahaan_id' => $validated['perusahaan_id'],
            'unit_id' => $unitId,
        ]);

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
            'perusahaan_id' => 'nullable|exists:perusahaan_penghasil,perusahaan_id',
            'tanggal_pengangkutan' => 'nullable|date',
            'jumlah_diangkut' => 'nullable|numeric|min:0',
            'status_log' => 'required|in:Tersimpan,Diangkut,Kadaluarsa',
        ]);

        // Unit_id tidak dapat diubah, tetap menggunakan yang sudah ada
        $validated['unit_id'] = $logPenyimpanan->unit_id;

        // Recalculate maximum storage date if waste entry date or type changed
        if ($logPenyimpanan->kode_limbah !== $validated['kode_limbah'] || $logPenyimpanan->tanggal_limbah_masuk !== $validated['tanggal_limbah_masuk']) {
            $jenisLimbah = JenisLimbah::where('kode_limbah', $validated['kode_limbah'])->first();
            if (! $jenisLimbah) {
                return back()->withErrors(['kode_limbah' => 'Jenis limbah tidak ditemukan.'])->withInput();
            }

            $tanggalMasuk = Carbon::parse($validated['tanggal_limbah_masuk']);
            $validated['maksimal_penyimpanan_tanggal'] = $tanggalMasuk->addDays($jenisLimbah->waktu_penyimpanan_hari);
        }

        $logPenyimpanan->update($validated);

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

        $logPenyimpanan->delete();

        return redirect()->route('log-penyimpanan.index')
            ->with('success', 'Log penyimpanan limbah berhasil dihapus.');
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
