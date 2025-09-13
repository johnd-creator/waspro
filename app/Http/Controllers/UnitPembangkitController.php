<?php

namespace App\Http\Controllers;

use App\Models\UnitPembangkit;
use Illuminate\Http\Request;

class UnitPembangkitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unitPembangkit = UnitPembangkit::paginate(10);

        return view('unit-pembangkit.index', compact('unitPembangkit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('unit-pembangkit.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_unit' => 'required|string|max:255|unique:unit_pembangkit,nama_unit',
            'alamat_unit' => 'required|string|max:500',
            'kota' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
        ]);

        UnitPembangkit::create($validated);

        return redirect()->route('unit-pembangkit.index')
            ->with('success', 'Unit pembangkit berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UnitPembangkit $unitPembangkit)
    {
        $unitPembangkit->load(['penggunaSistem', 'logPenyimpanan']);

        return view('unit-pembangkit.show', compact('unitPembangkit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UnitPembangkit $unitPembangkit)
    {
        return view('unit-pembangkit.edit', compact('unitPembangkit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnitPembangkit $unitPembangkit)
    {
        $validated = $request->validate([
            'nama_unit' => 'required|string|max:255|unique:unit_pembangkit,nama_unit,'.$unitPembangkit->unit_id.',unit_id',
            'alamat_unit' => 'required|string|max:500',
            'kota' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
        ]);

        $unitPembangkit->update($validated);

        return redirect()->route('unit-pembangkit.index')
            ->with('success', 'Unit pembangkit berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnitPembangkit $unitPembangkit)
    {
        $unitPembangkit->delete();

        return redirect()->route('unit-pembangkit.index')
            ->with('success', 'Unit pembangkit berhasil dihapus.');
    }
}
