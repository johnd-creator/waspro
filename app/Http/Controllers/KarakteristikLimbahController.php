<?php

namespace App\Http\Controllers;

use App\Models\KarakteristikLimbah;
use Illuminate\Http\Request;

class KarakteristikLimbahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karakteristikLimbah = KarakteristikLimbah::withCount('jenisLimbah')
            ->orderBy('nama_karakteristik')
            ->paginate(15);

        return view('karakteristik-limbah.index', compact('karakteristikLimbah'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karakteristik-limbah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_karakteristik' => 'required|string|max:255|unique:karakteristik_limbah,nama_karakteristik',
        ]);

        KarakteristikLimbah::create($validated);

        return redirect()->route('karakteristik-limbah.index')
            ->with('success', 'Karakteristik limbah berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KarakteristikLimbah $karakteristikLimbah)
    {
        $karakteristikLimbah->load('jenisLimbah');
        return view('karakteristik-limbah.show', compact('karakteristikLimbah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KarakteristikLimbah $karakteristikLimbah)
    {
        return view('karakteristik-limbah.edit', compact('karakteristikLimbah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KarakteristikLimbah $karakteristikLimbah)
    {
        $validated = $request->validate([
            'nama_karakteristik' => 'required|string|max:255|unique:karakteristik_limbah,nama_karakteristik,' . $karakteristikLimbah->karakteristik_id,
        ]);

        $karakteristikLimbah->update($validated);

        return redirect()->route('karakteristik-limbah.index')
            ->with('success', 'Karakteristik limbah berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KarakteristikLimbah $karakteristikLimbah)
    {
        // Check if this characteristic is being used by any waste types
        if ($karakteristikLimbah->jenisLimbah()->count() > 0) {
            return redirect()->route('karakteristik-limbah.index')
                ->with('error', 'Karakteristik limbah tidak dapat dihapus karena masih digunakan oleh jenis limbah.');
        }

        $karakteristikLimbah->delete();

        return redirect()->route('karakteristik-limbah.index')
            ->with('success', 'Karakteristik limbah berhasil dihapus.');
    }
}