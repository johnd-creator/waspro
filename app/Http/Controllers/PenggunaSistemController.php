<?php

namespace App\Http\Controllers;

use App\Models\PenggunaSistem;
use App\Models\PeranPengguna;
use App\Models\UnitPembangkit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaSistemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PenggunaSistem::with(['unitPembangkit', 'peranPengguna']);

        // Filter berdasarkan unit jika user bukan admin
        $currentUser = Auth::guard('web')->user();
        if ($currentUser && ! $this->isAdmin($currentUser)) {
            $query->where('unit_id', $currentUser->unit_id);
        }

        $users = $query->orderBy('nama_lengkap')->paginate(10);

        return view('pengguna-sistem.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currentUser = Auth::guard('web')->user();

        // Jika bukan admin, hanya bisa membuat user untuk unit sendiri
        if ($currentUser && ! $this->isAdmin($currentUser)) {
            $unitList = UnitPembangkit::where('unit_id', $currentUser->unit_id)->get();
        } else {
            $unitList = UnitPembangkit::orderBy('nama_unit')->get();
        }

        $peranList = PeranPengguna::orderBy('nama_peran')->get();

        return view('pengguna-sistem.create', compact('unitList', 'peranList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::guard('web')->user();

        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'email_address' => 'required|email|unique:pengguna_sistem,email_address',
            'kata_sandi' => 'required|string|min:8|confirmed',
            'unit_id' => 'required|exists:unit_pembangkit,unit_id',
            'peran_ids' => 'required|array|min:1',
            'peran_ids.*' => 'exists:peran_pengguna,peran_id',
            'aktif' => 'boolean',
        ];

        // Jika bukan admin, validasi unit_id harus sama dengan unit user
        if ($currentUser && ! $this->isAdmin($currentUser)) {
            $rules['unit_id'] = [
                'required',
                Rule::in([$currentUser->unit_id]),
            ];
        }

        $validated = $request->validate($rules);

        // Normalisasi nilai aktif: terima 'aktif' atau 'status_aktif' (backward-compat)
        $isAktif = $request->has('aktif') || $request->has('status_aktif')
            ? ($request->has('aktif') ? $request->boolean('aktif') : $request->boolean('status_aktif'))
            : true;

        // Buat user baru
        $user = PenggunaSistem::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'email_address' => $validated['email_address'],
            'kata_sandi_hash' => Hash::make($validated['kata_sandi']),
            'unit_id' => $validated['unit_id'],
            'aktif' => $isAktif,
        ]);

        // Attach peran
        $user->peranPengguna()->attach($validated['peran_ids']);

        return redirect()->route('pengguna-sistem.index')
            ->with('success', 'Pengguna sistem berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        // Cek akses berdasarkan unit
        if ($currentUser && ! $this->isAdmin($currentUser) && $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat pengguna dari unit lain.');
        }

        $penggunaSistem->load(['unitPembangkit', 'peranPengguna', 'logPenyimpananLimbah']);

        return view('pengguna-sistem.show', compact('penggunaSistem'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        // Cek akses berdasarkan unit
        if ($currentUser && ! $this->isAdmin($currentUser) && $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengguna dari unit lain.');
        }

        // Jika bukan admin, hanya bisa edit user untuk unit sendiri
        if ($currentUser && ! $this->isAdmin($currentUser)) {
            $unitList = UnitPembangkit::where('unit_id', $currentUser->unit_id)->get();
        } else {
            $unitList = UnitPembangkit::orderBy('nama_unit')->get();
        }

        $peranList = PeranPengguna::orderBy('nama_peran')->get();
        $userPeranIds = $penggunaSistem->peranPengguna()->pluck('peran_pengguna.peran_id')->toArray();

        return view('pengguna-sistem.edit', compact('penggunaSistem', 'unitList', 'peranList', 'userPeranIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        // Cek akses berdasarkan unit
        if ($currentUser && ! $this->isAdmin($currentUser) && $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengguna dari unit lain.');
        }

        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'email_address' => 'required|email|unique:pengguna_sistem,email_address,'.$penggunaSistem->user_id.',user_id',
            'unit_id' => 'required|exists:unit_pembangkit,unit_id',
            'peran_ids' => 'required|array|min:1',
            'peran_ids.*' => 'exists:peran_pengguna,peran_id',
            'aktif' => 'boolean',
        ];

        // Jika ada password baru
        if ($request->filled('kata_sandi')) {
            $rules['kata_sandi'] = 'string|min:8|confirmed';
        }

        // Jika bukan admin, validasi unit_id harus sama dengan unit user
        if ($currentUser && ! $this->isAdmin($currentUser)) {
            $rules['unit_id'] = [
                'required',
                Rule::in([$currentUser->unit_id]),
            ];
        }

        $validated = $request->validate($rules);

        // Normalisasi nilai aktif: terima 'aktif' atau 'status_aktif' (backward-compat)
        $isAktif = $request->has('aktif') || $request->has('status_aktif')
            ? ($request->has('aktif') ? $request->boolean('aktif') : $request->boolean('status_aktif'))
            : true;

        // Update data user
        $updateData = [
            'nama_lengkap' => $validated['nama_lengkap'],
            'email_address' => $validated['email_address'],
            'unit_id' => $validated['unit_id'],
            'aktif' => $isAktif,
        ];

        // Update password jika ada
        if ($request->filled('kata_sandi')) {
            $updateData['kata_sandi_hash'] = Hash::make($validated['kata_sandi']);
        }

        $penggunaSistem->update($updateData);

        // Sync peran
        $penggunaSistem->peranPengguna()->sync($validated['peran_ids']);

        return redirect()->route('pengguna-sistem.index')
            ->with('success', 'Pengguna sistem berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        // Cek akses berdasarkan unit
        if ($currentUser && ! $this->isAdmin($currentUser) && $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus pengguna dari unit lain.');
        }

        // Cek apakah user ini memiliki log penyimpanan
        if ($penggunaSistem->logPenyimpananLimbah()->count() > 0) {
            return redirect()->route('pengguna-sistem.index')
                ->with('error', 'Pengguna tidak dapat dihapus karena memiliki riwayat log penyimpanan limbah.');
        }

        // Hapus relasi peran
        $penggunaSistem->peranPengguna()->detach();

        // Hapus user
        $penggunaSistem->delete();

        return redirect()->route('pengguna-sistem.index')
            ->with('success', 'Pengguna sistem berhasil dihapus.');
    }

    /**
     * Toggle user status (aktif/nonaktif)
     */
    public function toggleStatus(PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        // Cek akses berdasarkan unit
        if ($currentUser && ! $this->isAdmin($currentUser) && $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah status pengguna dari unit lain.');
        }

        $penggunaSistem->update([
            'aktif' => ! $penggunaSistem->aktif,
        ]);

        $status = $penggunaSistem->aktif ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Pengguna berhasil {$status}.");
    }

    /**
     * Check if user is admin
     */
    private function isAdmin($user)
    {
        return $user->peranPengguna()->where('peran_pengguna.nama_peran', 'Admin')->exists();
    }
}
