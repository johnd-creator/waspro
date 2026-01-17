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
     * Display a listing of resource.
     */
    public function index(Request $request)
    {
        $query = PenggunaSistem::with(['unitPembangkit', 'peranPengguna']);

        $currentUser = Auth::guard('web')->user();
        if ($currentUser && ! $this->isAdmin($currentUser)) {
            $query->where('unit_id', $currentUser->unit_id);
        }

        $users = $query->orderBy('nama_lengkap')->paginate(10);

        return view('pengguna-sistem.index', compact('users'));
    }

    /**
     * Show form for creating a new resource.
     */
    public function create()
    {
        $currentUser = Auth::guard('web')->user();

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
            'unit_id' => 'nullable|exists:unit_pembangkit,unit_id',
            'peran_ids' => 'required|array|min:1',
            'peran_ids.*' => 'exists:peran_pengguna,peran_id',
            'aktif' => 'boolean',
        ];

        $isSuperAdminRole = in_array('Super Admin', $request->input('peran_ids', []));

        if ($isSuperAdminRole) {
            $superAdminPeranId = PeranPengguna::where('nama_peran', 'Super Admin')->first()?->peran_id;

            $existingSuperAdmin = PenggunaSistem::whereHas('peranPengguna', function ($q) use ($superAdminPeranId) {
                $q->where('peran_pengguna.peran_id', $superAdminPeranId);
            })->exists();

            if ($existingSuperAdmin) {
                return back()->withErrors(['peran_ids' => 'Hanya satu Super Admin yang diizinkan.'])->withInput();
            }

            $rules['peran_ids'] = [
                'required',
                'array',
                Rule::in([$superAdminPeranId]),
            ];

            if ($currentUser && ! $this->isAdmin($currentUser)) {
                return back()->withErrors(['peran_ids' => 'Anda tidak memiliki izin untuk membuat Super Admin.'])->withInput();
            }
        } else {
            $rules['unit_id'] = 'required|exists:unit_pembangkit,unit_id';

            $superAdminPeranId = PeranPengguna::where('nama_peran', 'Super Admin')->first()?->peran_id;
            $rules['peran_ids.*'] = function ($attribute, $value, $fail) use ($superAdminPeranId) {
                if ($value == $superAdminPeranId) {
                    $fail('Hanya satu Super Admin yang diizinkan.');
                }
            };

            if ($currentUser && ! $this->isAdmin($currentUser)) {
                $rules['unit_id'] = [
                    'required',
                    Rule::in([$currentUser->unit_id]),
                ];
            }
        }

        $validated = $request->validate($rules);

        $isAktif = $request->has('aktif') || $request->has('status_aktif')
            ? ($request->has('aktif') ? $request->boolean('aktif') : $request->boolean('status_aktif'))
            : true;

        $unitId = $isSuperAdminRole ? null : $validated['unit_id'];

        $user = PenggunaSistem::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'email_address' => $validated['email_address'],
            'kata_sandi_hash' => Hash::make($validated['kata_sandi']),
            'unit_id' => $unitId,
            'aktif' => $isAktif,
        ]);

        $user->peranPengguna()->attach($validated['peran_ids']);

        return redirect()->route('pengguna-sistem.index')
            ->with('success', 'Pengguna sistem berhasil ditambahkan.');
    }

    /**
     * Display specified resource.
     */
    public function show(PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        if ($currentUser && ! $this->isAdmin($currentUser) &&
            $penggunaSistem->unit_id !== null &&
            $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat pengguna dari unit lain.');
        }

        $penggunaSistem->load(['unitPembangkit', 'peranPengguna', 'logPenyimpananLimbah']);

        return view('pengguna-sistem.show', compact('penggunaSistem'));
    }

    /**
     * Show form for editing specified resource.
     */
    public function edit(PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        if ($currentUser && ! $this->isAdmin($currentUser) &&
            $penggunaSistem->unit_id !== null &&
            $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengguna dari unit lain.');
        }

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
     * Update specified resource in storage.
     */
    public function update(Request $request, PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        if ($currentUser && ! $this->isAdmin($currentUser) &&
            $penggunaSistem->unit_id !== null &&
            $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengguna dari unit lain.');
        }

        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'email_address' => 'required|email|unique:pengguna_sistem,email_address,'.$penggunaSistem->user_id.',user_id',
            'unit_id' => 'nullable|exists:unit_pembangkit,unit_id',
            'peran_ids' => 'required|array|min:1',
            'peran_ids.*' => 'exists:peran_pengguna,peran_id',
            'aktif' => 'boolean',
        ];

        if ($request->filled('kata_sandi')) {
            $rules['kata_sandi'] = 'string|min:8|confirmed';
        }

        $isBecomingSuperAdmin = in_array('Super Admin', $request->input('peran_ids', []));
        $isCurrentlySuperAdmin = $penggunaSistem->peranPengguna()->where('nama_peran', 'Super Admin')->exists();

        if ($isBecomingSuperAdmin) {
            $superAdminPeranId = PeranPengguna::where('nama_peran', 'Super Admin')->first()?->peran_id;
            $existingSuperAdmin = PenggunaSistem::whereHas('peranPengguna', function ($q) use ($superAdminPeranId) {
                $q->where('peran_pengguna.peran_id', $superAdminPeranId);
            })->where('user_id', '!=', $penggunaSistem->user_id)->exists();

            if ($existingSuperAdmin) {
                return back()->withErrors(['peran_ids' => 'Hanya satu Super Admin yang diizinkan.'])->withInput();
            }

            $rules['peran_ids'] = [
                'required',
                'array',
                Rule::in([$superAdminPeranId]),
            ];

            $rules['unit_id'] = 'nullable';

            if ($currentUser && ! $this->isAdmin($currentUser)) {
                return back()->withErrors(['peran_ids' => 'Anda tidak memiliki izin untuk mengubah pengguna menjadi Super Admin.'])->withInput();
            }
        } else {
            $rules['unit_id'] = 'required|exists:unit_pembangkit,unit_id';

            $superAdminPeranId = PeranPengguna::where('nama_peran', 'Super Admin')->first()?->peran_id;
            $rules['peran_ids.*'] = function ($attribute, $value, $fail) use ($superAdminPeranId) {
                if ($value == $superAdminPeranId) {
                    $fail('Hanya satu Super Admin yang diizinkan.');
                }
            };

            if ($currentUser && ! $this->isAdmin($currentUser)) {
                $rules['unit_id'] = [
                    'required',
                    Rule::in([$currentUser->unit_id]),
                ];
            }
        }

        $validated = $request->validate($rules);

        $isAktif = $request->has('aktif') || $request->has('status_aktif')
            ? ($request->has('aktif') ? $request->boolean('aktif') : $request->boolean('status_aktif'))
            : true;

        $unitId = $isBecomingSuperAdmin ? null : $validated['unit_id'];

        $updateData = [
            'nama_lengkap' => $validated['nama_lengkap'],
            'email_address' => $validated['email_address'],
            'unit_id' => $unitId,
            'aktif' => $isAktif,
        ];

        if ($request->filled('kata_sandi')) {
            $updateData['kata_sandi_hash'] = Hash::make($validated['kata_sandi']);
        }

        $penggunaSistem->update($updateData);

        $penggunaSistem->peranPengguna()->sync($validated['peran_ids']);

        return redirect()->route('pengguna-sistem.index')
            ->with('success', 'Pengguna sistem berhasil diperbarui.');
    }

    /**
     * Remove specified resource from storage.
     */
    public function destroy(PenggunaSistem $penggunaSistem)
    {
        $currentUser = Auth::guard('web')->user();

        if ($currentUser && ! $this->isAdmin($currentUser) &&
            $penggunaSistem->unit_id !== null &&
            $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus pengguna dari unit lain.');
        }

        if ($penggunaSistem->peranPengguna()->where('nama_peran', 'Super Admin')->exists()) {
            return redirect()->route('pengguna-sistem.index')
                ->with('error', 'Super Admin tidak dapat dihapus.');
        }

        if ($penggunaSistem->logPenyimpananLimbah()->count() > 0) {
            return redirect()->route('pengguna-sistem.index')
                ->with('error', 'Pengguna tidak dapat dihapus karena memiliki riwayat log penyimpanan limbah.');
        }

        $penggunaSistem->peranPengguna()->detach();

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

        if ($currentUser && ! $this->isAdmin($currentUser) &&
            $penggunaSistem->unit_id !== null &&
            $penggunaSistem->unit_id !== $currentUser->unit_id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah status pengguna dari unit lain.');
        }

        if ($penggunaSistem->peranPengguna()->where('nama_peran', 'Super Admin')->exists()) {
            return redirect()->route('pengguna-sistem.index')
                ->with('error', 'Status Super Admin tidak dapat diubah.');
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
        return $user->peranPengguna()->whereIn('peran_pengguna.nama_peran', ['Super Admin', 'Administrator'])->exists();
    }
}
