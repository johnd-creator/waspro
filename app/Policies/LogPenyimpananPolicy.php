<?php

namespace App\Policies;

use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogPenyimpananPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(PenggunaSistem $user): bool
    {
        // Semua role bisa melihat daftar log (dengan filter unit masing-masing)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(PenggunaSistem $user, LogPenyimpananLimbah $logPenyimpanan): bool
    {
        // Super Admin bisa melihat semua
        if ($user->isSuperAdmin()) {
            return true;
        }

        // User lain hanya bisa melihat log dari unit sendiri
        return $user->unit_id === $logPenyimpanan->unit_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(PenggunaSistem $user): bool
    {
        // Viewer tidak bisa membuat log
        return ! $user->isViewer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(PenggunaSistem $user, LogPenyimpananLimbah $logPenyimpanan): bool
    {
        // Viewer tidak bisa mengupdate
        if ($user->isViewer()) {
            return false;
        }

        // Super Admin bisa mengupdate semua
        if ($user->isSuperAdmin()) {
            return true;
        }

        // User lain hanya bisa mengupdate log dari unit sendiri
        return $user->unit_id === $logPenyimpanan->unit_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(PenggunaSistem $user, LogPenyimpananLimbah $logPenyimpanan): bool
    {
        // Hanya Administrator dan Super Admin yang bisa menghapus
        if (! $user->isAdministrator() && ! $user->isSuperAdmin()) {
            return false;
        }

        // Super Admin bisa menghapus semua
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Administrator hanya bisa menghapus log dari unit sendiri
        return $user->unit_id === $logPenyimpanan->unit_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(PenggunaSistem $user, LogPenyimpananLimbah $logPenyimpanan): bool
    {
        return $this->delete($user, $logPenyimpanan);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(PenggunaSistem $user, LogPenyimpananLimbah $logPenyimpanan): bool
    {
        // Hanya Super Admin yang bisa force delete
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can export data.
     */
    public function export(PenggunaSistem $user): bool
    {
        // Semua role kecuali Viewer bisa export (dengan filter unit masing-masing)
        return ! $user->isViewer();
    }

    /**
     * Determine whether the user can import data.
     */
    public function import(PenggunaSistem $user): bool
    {
        // Hanya Administrator dan Super Admin yang bisa import
        return $user->isAdministrator() || $user->isSuperAdmin();
    }
}
