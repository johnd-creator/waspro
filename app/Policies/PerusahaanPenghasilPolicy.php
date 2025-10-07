<?php

namespace App\Policies;

use App\Models\PenggunaSistem;
use App\Models\PerusahaanPenghasil;
use Illuminate\Auth\Access\HandlesAuthorization;

class PerusahaanPenghasilPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(PenggunaSistem $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(PenggunaSistem $user, PerusahaanPenghasil $perusahaanPenghasil): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(PenggunaSistem $user): bool
    {
        return $user->isAdministrator() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(PenggunaSistem $user, PerusahaanPenghasil $perusahaanPenghasil): bool
    {
        return $user->isAdministrator() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(PenggunaSistem $user, PerusahaanPenghasil $perusahaanPenghasil): bool
    {
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(PenggunaSistem $user, PerusahaanPenghasil $perusahaanPenghasil): bool
    {
        return $this->delete($user, $perusahaanPenghasil);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(PenggunaSistem $user, PerusahaanPenghasil $perusahaanPenghasil): bool
    {
        return $this->delete($user, $perusahaanPenghasil);
    }
}
