<?php

namespace App\Http\Controllers;

use App\Models\JenisLimbah;
use App\Models\KarakteristikLimbah;
use App\Models\KategoriKegiatanSumber;
use Illuminate\Http\Request;

class JenisLimbahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jenisLimbah = JenisLimbah::with(['karakteristik', 'kategoriKegiatanSumber'])
            ->orderBy('kode_limbah')
            ->paginate(15);

        return view('jenis-limbah.index', compact('jenisLimbah'));
    }

    /**
     * Show form for creating a new resource.
     */
    public function create()
    {
        $karakteristikLimbah = KarakteristikLimbah::all();
        $kategoriKegiatanSumber = KategoriKegiatanSumber::orderBy('nama_kategori')->get();

        return view('jenis-limbah.create', compact('karakteristikLimbah', 'kategoriKegiatanSumber'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_limbah' => 'required|string|max:10|unique:jenis_limbah,kode_limbah',
            'nama_limbah' => 'required|string|max:255',
            'kemasan' => 'required|string|max:255',
            'deskripsi_limbah' => 'nullable|string|max:500',
            'waktu_penyimpanan_hari' => 'required|integer|min:1|max:365',
            'karakteristik_id' => 'required|exists:karakteristik_limbah,karakteristik_id',
            'kategori_id' => 'required|exists:kategori_kegiatan_sumber,kategori_id',
            'status_aktif' => 'required|boolean',
            'biaya_pengangkutan_per_kg' => 'nullable|numeric|min:0',
            'mulai_berlaku' => 'nullable|date|after_or_equal:today',
            'akhir_berlaku' => 'nullable|date|after:mulai_berlaku',
            'keterangan_biaya' => 'nullable|array',
            'batas_penyimpanan_hari' => 'nullable|integer|min:1|max:365',
        ]);

        // Sinkronkan waktu_penyimpanan_hari dengan batas_penyimpanan_hari
        $validated['batas_penyimpanan_hari'] = $validated['waktu_penyimpanan_hari'];

        JenisLimbah::create($validated);

        return redirect()->route('jenis-limbah.index')
            ->with('success', 'Jenis limbah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisLimbah $jenisLimbah)
    {
        $jenisLimbah->load(['karakteristik', 'kategoriKegiatanSumber', 'logPenyimpanan']);

        return view('jenis-limbah.show', compact('jenisLimbah'));
    }

    /**
     * Show form for editing specified resource.
     */
    public function edit(JenisLimbah $jenisLimbah)
    {
        $karakteristikLimbah = KarakteristikLimbah::all();
        $kategoriKegiatanSumber = KategoriKegiatanSumber::orderBy('nama_kategori')->get();

        return view('jenis-limbah.edit', compact('jenisLimbah', 'karakteristikLimbah', 'kategoriKegiatanSumber'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisLimbah $jenisLimbah)
    {
        $validated = $request->validate([
            'kode_limbah' => 'required|string|max:10|unique:jenis_limbah,kode_limbah,'.$jenisLimbah->kode_limbah.',kode_limbah',
            'nama_limbah' => 'required|string|max:255',
            'kemasan' => 'required|string|max:255',
            'deskripsi_limbah' => 'nullable|string|max:500',
            'waktu_penyimpanan_hari' => 'required|integer|min:1|max:365',
            'karakteristik_id' => 'required|exists:karakteristik_limbah,karakteristik_id',
            'kategori_id' => 'required|exists:kategori_kegiatan_sumber,kategori_id',
            'status_aktif' => 'required|boolean',
            'biaya_pengangkutan_per_kg' => 'nullable|numeric|min:0',
            'mulai_berlaku' => 'nullable|date|after_or_equal:today',
            'akhir_berlaku' => 'nullable|date|after:mulai_berlaku',
            'keterangan_biaya' => 'nullable|array',
        ]);

        // Sinkronkan waktu_penyimpanan_hari dengan batas_penyimpanan_hari
        $validated['batas_penyimpanan_hari'] = $validated['waktu_penyimpanan_hari'];

        $jenisLimbah->update($validated);

        return redirect()->route('jenis-limbah.show', $jenisLimbah)
            ->with('success', 'Jenis limbah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisLimbah $jenisLimbah)
    {
        // Check if this waste type is being used in any logs
        if ($jenisLimbah->logPenyimpanan()->count() > 0) {
            return redirect()->route('jenis-limbah.index')
                ->with('error', 'Jenis limbah tidak dapat dihapus karena masih digunakan dalam log penyimpanan.');
        }

        $jenisLimbah->delete();

        return redirect()->route('jenis-limbah.index')
            ->with('success', 'Jenis limbah berhasil dihapus.');
    }
}
