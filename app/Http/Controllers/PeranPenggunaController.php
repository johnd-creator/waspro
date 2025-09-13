<?php

namespace App\Http\Controllers;

use App\Http\Traits\HandlesValidation;
use App\Models\PeranPengguna;
use Illuminate\Http\Request;

class PeranPenggunaController extends Controller
{
    use HandlesValidation;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PeranPengguna::query();

        // Search functionality
        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_peran', 'like', '%'.$search.'%')
                    ->orWhere('deskripsi', 'like', '%'.$search.'%');
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
        $rules = [
            'nama_peran' => 'required|string|max:255|unique:peran_pengguna,nama_peran',
            'deskripsi' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];

        $messages = [
            'nama_peran.required' => 'Nama peran wajib diisi.',
            'nama_peran.unique' => 'Nama peran sudah digunakan.',
            'nama_peran.max' => 'Nama peran maksimal 255 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
        ];

        $validated = $this->validateRequest($request, $rules, $messages);

        if (is_array($validated) && ! $validated['success']) {
            return $validated;
        }

        return $this->handleDatabaseOperation(
            function () use ($validated, $request) {
                return PeranPengguna::create([
                    'nama_peran' => $validated['nama_peran'],
                    'deskripsi' => $validated['deskripsi'] ?? null,
                    'is_active' => $request->has('is_active'),
                ]);
            },
            'Peran pengguna berhasil ditambahkan.',
            'Gagal menambahkan peran pengguna',
            'peran-pengguna.index'
        );
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

        $rules = [
            'nama_peran' => 'required|string|max:255|unique:peran_pengguna,nama_peran,'.$peran_id.',peran_id',
            'deskripsi' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ];

        $messages = [
            'nama_peran.required' => 'Nama peran wajib diisi.',
            'nama_peran.unique' => 'Nama peran sudah digunakan.',
            'nama_peran.max' => 'Nama peran maksimal 255 karakter.',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
        ];

        $validated = $this->validateRequest($request, $rules, $messages);

        if (is_array($validated) && ! $validated['success']) {
            return $validated;
        }

        return $this->handleDatabaseOperation(
            function () use ($peranPengguna, $validated, $request) {
                return $peranPengguna->update([
                    'nama_peran' => $validated['nama_peran'],
                    'deskripsi' => $validated['deskripsi'] ?? null,
                    'is_active' => $request->has('is_active'),
                ]);
            },
            'Peran pengguna berhasil diperbarui.',
            'Gagal memperbarui peran pengguna',
            'peran-pengguna.index'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($peran_id)
    {
        $peranPengguna = PeranPengguna::findOrFail($peran_id);

        // Check if role is being used by users
        if ($peranPengguna->penggunaSistem()->count() > 0) {
            return $this->errorResponse(
                'Peran pengguna tidak dapat dihapus karena masih digunakan oleh pengguna.',
                400
            );
        }

        return $this->handleDatabaseOperation(
            function () use ($peranPengguna) {
                return $peranPengguna->delete();
            },
            'Peran pengguna berhasil dihapus.',
            'Gagal menghapus peran pengguna',
            'peran-pengguna.index'
        );
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
