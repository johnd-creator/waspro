<?php

namespace App\Http\Controllers;

use App\Models\KategoriKegiatanSumber;
use Illuminate\Http\Request;

class KategoriKegiatanSumberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoriKegiatanSumber = KategoriKegiatanSumber::withCount('jenisLimbah')
            ->orderBy('nama_kategori')
            ->paginate(15);

        return view('kategori-kegiatan-sumber.index', compact('kategoriKegiatanSumber'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori-kegiatan-sumber.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_kegiatan_sumber,nama_kategori',
        ]);

        KategoriKegiatanSumber::create($validated);

        return redirect()->route('kategori-kegiatan-sumber.index')
            ->with('success', 'Kategori kegiatan sumber berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KategoriKegiatanSumber $kategoriKegiatanSumber)
    {
        $kategoriKegiatanSumber->load('jenisLimbah');
        return view('kategori-kegiatan-sumber.show', compact('kategoriKegiatanSumber'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriKegiatanSumber $kategoriKegiatanSumber)
    {
        return view('kategori-kegiatan-sumber.edit', compact('kategoriKegiatanSumber'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KategoriKegiatanSumber $kategoriKegiatanSumber)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategori_kegiatan_sumber,nama_kategori,' . $kategoriKegiatanSumber->kategori_id,
        ]);

        $kategoriKegiatanSumber->update($validated);

        return redirect()->route('kategori-kegiatan-sumber.index')
            ->with('success', 'Kategori kegiatan sumber berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriKegiatanSumber $kategoriKegiatanSumber)
    {
        // Check if this category is being used by any waste types
        if ($kategoriKegiatanSumber->jenisLimbah()->count() > 0) {
            return redirect()->route('kategori-kegiatan-sumber.index')
                ->with('error', 'Kategori kegiatan sumber tidak dapat dihapus karena masih digunakan oleh jenis limbah.');
        }

        $kategoriKegiatanSumber->delete();

        return redirect()->route('kategori-kegiatan-sumber.index')
            ->with('success', 'Kategori kegiatan sumber berhasil dihapus.');
    }
}