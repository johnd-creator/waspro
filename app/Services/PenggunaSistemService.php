<?php

namespace App\Services;

use App\Models\PenggunaSistem;
use App\Models\PeranPengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PenggunaSistemService
{
    public function createUser(array $data, array $peranIds): PenggunaSistem
    {
        return DB::transaction(function () use ($data, $peranIds) {
            $isAktif = $this->getAktifValue($data);
            $unitId = $this->determineUnitId($data, $peranIds);

            $user = PenggunaSistem::create([
                'nama_lengkap' => $data['nama_lengkap'],
                'email_address' => $data['email_address'],
                'kata_sandi_hash' => Hash::make($data['kata_sandi']),
                'unit_id' => $unitId,
                'aktif' => $isAktif,
            ]);

            $user->peranPengguna()->attach($peranIds);

            return $user;
        });
    }

    public function updateUser(PenggunaSistem $user, array $data, ?array $peranIds): PenggunaSistem
    {
        return DB::transaction(function () use ($user, $data, $peranIds) {
            $updateData = [
                'nama_lengkap' => $data['nama_lengkap'],
                'email_address' => $data['email_address'],
            ];

            if ($this->getUnitId($data)) {
                $updateData['unit_id'] = $this->getUnitId($data);
            }

            $isAktif = $this->getAktifValue($data);
            if ($isAktif !== null) {
                $updateData['aktif'] = $isAktif;
            }

            if (isset($data['kata_sandi'])) {
                $updateData['kata_sandi_hash'] = Hash::make($data['kata_sandi']);
            }

            $user->update($updateData);

            if ($peranIds !== null) {
                $user->peranPengguna()->sync($peranIds);
            }

            return $user->fresh();
        });
    }

    public function checkSuperAdminConstraint(array $peranIds, ?PenggunaSistem $excludeUser = null): ?array
    {
        if (!in_array('Super Admin', $peranIds)) {
            return null;
        }

        $superAdminPeranId = PeranPengguna::where('nama_peran', 'Super Admin')->first()?->peran_id;

        if (!$superAdminPeranId) {
            return null;
        }

        $query = PenggunaSistem::whereHas('peranPengguna', function ($q) use ($superAdminPeranId) {
            $q->where('peran_pengguna.peran_id', $superAdminPeranId);
        });

        if ($excludeUser) {
            $query->where('user_id', '!=', $excludeUser->user_id);
        }

        $existingSuperAdmin = $query->exists();

        if ($existingSuperAdmin) {
            return [
                'error' => 'Hanya satu Super Admin yang diizinkan.',
                'peran_ids' => 'Hanya satu Super Admin yang diizinkan.',
            ];
        }

        return null;
    }

    public function checkSuperAdminModificationPermission(?PenggunaSistem $currentUser, array $peranIds, ?PenggunaSistem $targetUser = null): bool
    {
        if (!in_array('Super Admin', $peranIds)) {
            return true;
        }

        if (!$currentUser || $this->isAdmin($currentUser)) {
            return true;
        }

        if ($targetUser && $targetUser->user_id === $currentUser->user_id) {
            return true;
        }

        return false;
    }

    public function checkCrossUnitAccess(?PenggunaSistem $currentUser, ?PenggunaSistem $targetUser): bool
    {
        if (!$currentUser || $this->isAdmin($currentUser)) {
            return true;
        }

        if (!$targetUser || $targetUser->unit_id === null) {
            return true;
        }

        return $targetUser->unit_id === $currentUser->unit_id;
    }

    public function getAktifValue(array $data): ?bool
    {
        if (isset($data['aktif'])) {
            return $data['aktif'];
        }

        if (isset($data['status_aktif'])) {
            return $data['status_aktif'];
        }

        return true;
    }

    public function getUnitId(array $data): ?int
    {
        return $data['unit_id'] ?? null;
    }

    protected function determineUnitId(array $data, array $peranIds): ?int
    {
        if (in_array('Super Admin', $peranIds)) {
            return null;
        }

        return $this->getUnitId($data);
    }

    public function isAdmin(PenggunaSistem $user): bool
    {
        return $user->peranPengguna()->where('nama_peran', 'Super Admin')->exists() ||
               $user->peranPengguna()->where('nama_peran', 'Administrator')->exists();
    }

    public function getUsersWithFilters(array $filters, int $perPage = 10)
    {
        $query = PenggunaSistem::with(['unitPembangkit', 'peranPengguna']);
        $currentUser = Auth::guard('web')->user();

        if ($currentUser && !$this->isAdmin($currentUser)) {
            $query->where('unit_id', $currentUser->unit_id);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama_lengkap', 'LIKE', '%' . $filters['search'] . '%')
                    ->orWhere('email_address', 'LIKE', '%' . $filters['search'] . '%');
            });
        }

        if (isset($filters['peran'])) {
            $query->whereHas('peranPengguna', function ($q) use ($filters) {
                $q->where('nama_peran', $filters['peran']);
            });
        }

        if (isset($filters['aktif'])) {
            $query->where('aktif', $filters['aktif']);
        }

        return $query->orderBy('nama_lengkap')->paginate($perPage);
    }

    public function getFilteredUnitList(?PenggunaSistem $currentUser)
    {
        if ($currentUser && !$this->isAdmin($currentUser)) {
            return \App\Models\UnitPembangkit::where('unit_id', $currentUser->unit_id)->get();
        }

        return \App\Models\UnitPembangkit::orderBy('nama_unit')->get();
    }

    public function canDeleteUser(PenggunaSistem $currentUser, PenggunaSistem $targetUser): bool
    {
        if (!$currentUser || $this->isAdmin($currentUser)) {
            return true;
        }

        if ($targetUser->unit_id === null || $targetUser->unit_id !== $currentUser->unit_id) {
            return false;
        }

        return true;
    }
}
