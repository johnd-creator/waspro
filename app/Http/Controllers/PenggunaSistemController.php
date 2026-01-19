<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenggunaSistemRequest;
use App\Http\Requests\UpdatePenggunaSistemRequest;
use App\Services\PenggunaSistemService;
use App\Models\PenggunaSistem;
use App\Models\UnitPembangkit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PenggunaSistemController extends Controller
{
    protected PenggunaSistemService $userService;

    public function __construct(PenggunaSistemService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'peran', 'aktif']);
        $users = $this->userService->getUsersWithFilters($filters);
        
        return view('pengguna-sistem.index', compact('users'));
    }

    public function create()
    {
        $currentUser = Auth::guard('web')->user();
        $unitList = $this->userService->getFilteredUnitList($currentUser);
        $peranList = \App\Models\PeranPengguna::orderBy('nama_peran')->get();
        
        return view('pengguna-sistem.create', compact('unitList', 'peranList'));
    }

    public function store(StorePenggunaSistemRequest $request)
    {
        $user = Auth::guard('web')->user();
        
        $superAdminConstraint = $this->userService->checkSuperAdminConstraint(
            $request->input('peran_ids'),
            null
        );

        if ($superAdminConstraint) {
            return back()->withErrors($superAdminConstraint)->withInput();
        }

        $permissionCheck = $this->userService->checkSuperAdminModificationPermission(
            $user,
            $request->input('peran_ids')
        );

        if (!$permissionCheck) {
            return back()->withErrors([
                'peran_ids' => 'Anda tidak memiliki izin untuk membuat Super Admin.'
            ])->withInput();
        }

        $validated = $request->validated();
        $isAktif = $this->userService->getAktifValue($validated);
        $unitId = $this->userService->getUnitId($validated);
        
        $createdUser = $this->userService->createUser($validated, $validated['peran_ids']);

        return redirect()->route('pengguna-sistem.index')
            ->with('success', 'Pengguna sistem berhasil ditambahkan.');
    }

    public function show(PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        if (!$this->userService->isAdmin($currentUser) &&
            $penggunaSistem->unit_id !== null &&
            $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat pengguna dari unit lain.');
        }

        $penggunaSistem->load(['unitPembangkit', 'peranPengguna', 'logPenyimpananLimbah']);

        return view('pengguna-sistem.show', compact('penggunaSistem'));
    }

    public function edit(PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        if (!$this->userService->isAdmin($currentUser) &&
            $penggunaSistem->unit_id !== null &&
            $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengguna dari unit lain.');
        }

        $unitList = $this->userService->getFilteredUnitList($currentUser);
        $peranList = \App\Models\PeranPengguna::orderBy('nama_peran')->get();
        $userPeranIds = $penggunaSistem->peranPengguna()->pluck('peran_pengguna.peran_id')->toArray();

        return view('pengguna-sistem.edit', compact('penggunaSistem', 'unitList', 'peranList', 'userPeranIds'));
    }

    public function update(UpdatePenggunaSistemRequest $request, PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        if (!$this->userService->isAdmin($currentUser) &&
            $penggunaSistem->unit_id !== null &&
            $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengguna dari unit lain.');
        }

        $superAdminConstraint = $this->userService->checkSuperAdminConstraint(
            $request->input('peran_ids'),
            $penggunaSistem
        );

        if ($superAdminConstraint) {
            return back()->withErrors($superAdminConstraint)->withInput();
        }

        $permissionCheck = $this->userService->checkSuperAdminModificationPermission(
            $currentUser,
            $request->input('peran_ids'),
            $penggunaSistem
        );

        if (!$permissionCheck) {
            return back()->withErrors([
                'peran_ids' => 'Anda tidak memiliki izin untuk mengubah pengguna menjadi Super Admin.'
            ])->withInput();
        }

        $validated = $request->validated();
        $peranIds = $request->filled('peran_ids') ? $validated['peran_ids'] : null;

        $this->userService->updateUser($penggunaSistem, $validated, $peranIds);

        return redirect()->route('pengguna-sistem.index')
            ->with('success', 'Pengguna sistem berhasil diperbarui.');
    }

    public function destroy(PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        if (!$this->userService->canDeleteUser($currentUser, $penggunaSistem)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus pengguna dari unit lain.');
        }

        $penggunaSistem->delete();

        return redirect()->route('pengguna-sistem.index')
            ->with('success', 'Pengguna sistem berhasil dihapus.');
    }

    public function resetPassword(Request $request, PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        if (!$this->userService->isAdmin($currentUser) &&
            $penggunaSistem->unit_id !== null &&
            $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk mereset kata sandi pengguna dari unit lain.');
        }

        $newPassword = 'password' . rand(1000, 9999);
        
        $penggunaSistem->update([
            'kata_sandi_hash' => Hash::make($newPassword),
            'password_changed_at' => now(),
        ]);

        return redirect()->route('pengguna-sistem.index')
            ->with('success', 'Kata sandi berhasil di-reset. Kata sandi sementara: ' . $newPassword);
    }
}
