<?php

namespace App\Http\Controllers;

use App\Models\PerusahaanPenghasil;
use Illuminate\Http\Request;

class PerusahaanPenghasilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perusahaanPenghasil = PerusahaanPenghasil::orderBy('nama_perusahaan', 'asc')
            ->paginate(10);

        return view('perusahaan-penghasil.index', compact('perusahaanPenghasil'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('perusahaan-penghasil.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255|unique:perusahaan_penghasil,nama_perusahaan',
            'jenis_perusahaan' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'kota' => 'nullable|string|max:50',
            'alamat_perusahaan' => 'required|string|max:500',
            'person_in_charge' => 'nullable|string|max:100',
            'status_aktif' => 'required|boolean',
            'keterangan' => 'nullable|string|max:500',
        ]);

        PerusahaanPenghasil::create($validated);

        return redirect()->route('perusahaan-penghasil.index')
            ->with('success', 'Perusahaan penghasil berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PerusahaanPenghasil $perusahaanPenghasil)
    {
        $perusahaanPenghasil->load('logPenyimpanan.jenisLimbah');

        return view('perusahaan-penghasil.show', compact('perusahaanPenghasil'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PerusahaanPenghasil $perusahaanPenghasil)
    {
        return view('perusahaan-penghasil.edit', compact('perusahaanPenghasil'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PerusahaanPenghasil $perusahaanPenghasil)
    {
        $validated = $request->validate([
            'nama_perusahaan' => 'required|string|max:255|unique:perusahaan_penghasil,nama_perusahaan,'.$perusahaanPenghasil->perusahaan_id.',perusahaan_id',
            'jenis_perusahaan' => 'nullable|string|max:255',
            'telepon' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'kota' => 'nullable|string|max:50',
            'alamat_perusahaan' => 'required|string|max:500',
            'person_in_charge' => 'nullable|string|max:100',
            'status_aktif' => 'required|boolean',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $perusahaanPenghasil->update($validated);

        return redirect()->route('perusahaan-penghasil.index')
            ->with('success', 'Perusahaan penghasil berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PerusahaanPenghasil $perusahaanPenghasil)
    {
        // Check if this company is being used in any logs
        if ($perusahaanPenghasil->logPenyimpanan()->count() > 0) {
            return redirect()->route('perusahaan-penghasil.index')
                ->with('error', 'Perusahaan penghasil tidak dapat dihapus karena masih digunakan dalam log penyimpanan.');
        }

        $perusahaanPenghasil->delete();

        return redirect()->route('perusahaan-penghasil.index')
            ->with('success', 'Perusahaan penghasil berhasil dihapus.');
    }
}
