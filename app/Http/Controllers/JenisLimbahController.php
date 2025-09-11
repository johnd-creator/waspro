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
        $jenisLimbah = JenisLimbah::with(['karakteristik', 'kategori'])
            ->orderBy('kode_limbah')
            ->paginate(15);

        return view('jenis-limbah.index', compact('jenisLimbah'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $karakteristikLimbah = KarakteristikLimbah::all();
        $kategoriKegiatanSumber = KategoriKegiatanSumber::all();

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
            'kemasan' => 'required|string|max:100',
            'jumlah_ton_per_tahun' => 'required|numeric|min:0',
            'waktu_penyimpanan_hari' => 'required|integer|min:1|max:365',
            'karakteristik_id' => 'required|exists:karakteristik_limbah,karakteristik_id',
            'kategori_id' => 'required|exists:kategori_kegiatan_sumber,kategori_id',
        ]);

        JenisLimbah::create($validated);

        return redirect()->route('jenis-limbah.index')
            ->with('success', 'Jenis limbah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisLimbah $jenisLimbah)
    {
        $jenisLimbah->load(['karakteristik', 'kategori', 'logPenyimpanan']);
        return view('jenis-limbah.show', compact('jenisLimbah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisLimbah $jenisLimbah)
    {
        $karakteristikLimbah = KarakteristikLimbah::all();
        $kategoriKegiatanSumber = KategoriKegiatanSumber::all();

        return view('jenis-limbah.edit', compact('jenisLimbah', 'karakteristikLimbah', 'kategoriKegiatanSumber'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisLimbah $jenisLimbah)
    {
        $validated = $request->validate([
            'kode_limbah' => 'required|string|max:10|unique:jenis_limbah,kode_limbah,' . $jenisLimbah->kode_limbah . ',kode_limbah',
            'nama_limbah' => 'required|string|max:255',
            'kemasan' => 'required|string|max:100',
            'jumlah_ton_per_tahun' => 'required|numeric|min:0',
            'waktu_penyimpanan_hari' => 'required|integer|min:1|max:365',
            'karakteristik_id' => 'required|exists:karakteristik_limbah,karakteristik_id',
            'kategori_id' => 'required|exists:kategori_kegiatan_sumber,kategori_id',
        ]);

        $jenisLimbah->update($validated);

        return redirect()->route('jenis-limbah.index')
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