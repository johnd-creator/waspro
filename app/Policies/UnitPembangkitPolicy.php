<?php

namespace App\Policies;

use App\Models\UnitPembangkit;
use App\Models\PenggunaSistem;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnitPembangkitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(PenggunaSistem $user): bool
    {
        // Semua role bisa melihat daftar unit (dengan filter sesuai akses)
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(PenggunaSistem $user, UnitPembangkit $unitPembangkit): bool
    {
        // Super Admin bisa melihat semua unit
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // User lain hanya bisa melihat unit sendiri
        return $user->unit_id === $unitPembangkit->unit_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(PenggunaSistem $user): bool
    {
        // Hanya Super Admin yang bisa membuat unit baru
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(PenggunaSistem $user, UnitPembangkit $unitPembangkit): bool
    {
        // Super Admin bisa mengupdate semua unit
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Administrator hanya bisa mengupdate unit sendiri
        if ($user->isAdministrator()) {
            return $user->unit_id === $unitPembangkit->unit_id;
        }
        
        // Operator dan Viewer tidak bisa mengupdate unit
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(PenggunaSistem $user, UnitPembangkit $unitPembangkit): bool
    {
        // Hanya Super Admin yang bisa menghapus unit
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(PenggunaSistem $user, UnitPembangkit $unitPembangkit): bool
    {
        return $this->delete($user, $unitPembangkit);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(PenggunaSistem $user, UnitPembangkit $unitPembangkit): bool
    {
        // Hanya Super Admin yang bisa force delete
        return $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can manage unit settings.
     */
    public function manageSettings(PenggunaSistem $user, UnitPembangkit $unitPembangkit): bool
    {
        // Super Admin bisa mengelola settings semua unit
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Administrator hanya bisa mengelola settings unit sendiri
        if ($user->isAdministrator()) {
            return $user->unit_id === $unitPembangkit->unit_id;
        }
        
        return false;
    }

    /**
     * Determine whether the user can view unit reports.
     */
    public function viewReports(PenggunaSistem $user, UnitPembangkit $unitPembangkit): bool
    {
        // Super Admin bisa melihat laporan semua unit
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // User lain hanya bisa melihat laporan unit sendiri
        return $user->unit_id === $unitPembangkit->unit_id;
    }
}