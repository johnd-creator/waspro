<?php

namespace App\Http\Controllers;

use App\Models\PeranPengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeranPenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PeranPengguna::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_peran', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $peranPengguna = $query->orderBy('nama_peran')->paginate(10);
        
        return view('peran-pengguna.index', compact('peranPengguna'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('peran-pengguna.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_peran' => 'required|string|max:255|unique:peran_pengguna,nama_peran',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        PeranPengguna::create([
            'nama_peran' => $request->nama_peran,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('peran-pengguna.index')
            ->with('success', 'Peran pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($peran_id)
    {
        $peranPengguna = PeranPengguna::findOrFail($peran_id);
        return view('peran-pengguna.show', compact('peranPengguna'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($peran_id)
    {
        $peranPengguna = PeranPengguna::findOrFail($peran_id);
        return view('peran-pengguna.edit', compact('peranPengguna'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $peran_id)
    {
        $peranPengguna = PeranPengguna::findOrFail($peran_id);
        
        $validator = Validator::make($request->all(), [
            'nama_peran' => 'required|string|max:255|unique:peran_pengguna,nama_peran,' . $peran_id . ',peran_id',
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $peranPengguna->update([
            'nama_peran' => $request->nama_peran,
            'deskripsi' => $request->deskripsi,
            'is_active' => $request->has('is_active') ? true : false
        ]);

        return redirect()->route('peran-pengguna.index')
            ->with('success', 'Peran pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($peran_id)
    {
        $peranPengguna = PeranPengguna::findOrFail($peran_id);
        
        // Check if role is being used by users
        if ($peranPengguna->penggunaSistem()->count() > 0) {
            return redirect()->route('peran-pengguna.index')
                ->with('error', 'Peran pengguna tidak dapat dihapus karena masih digunakan oleh pengguna.');
        }
        
        $peranPengguna->delete();
        
        return redirect()->route('peran-pengguna.index')
            ->with('success', 'Peran pengguna berhasil dihapus.');
    }

    /**
     * Toggle the active status of the role.
     */
    public function toggleStatus($peran_id)
    {
        $peranPengguna = PeranPengguna::findOrFail($peran_id);
        $peranPengguna->toggleStatus();
        
        $status = $peranPengguna->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('peran-pengguna.index')
            ->with('success', "Peran pengguna berhasil {$status}.");
    }
}
