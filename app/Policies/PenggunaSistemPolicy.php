<?php

namespace App\Policies;

use App\Models\PenggunaSistem;
use Illuminate\Auth\Access\HandlesAuthorization;

class PenggunaSistemPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(PenggunaSistem $user): bool
    {
        // Operator tidak bisa mengakses manajemen pengguna
        return !$user->isOperator();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(PenggunaSistem $user, PenggunaSistem $penggunaSistem): bool
    {
        // Operator tidak bisa melihat data pengguna lain
        if ($user->isOperator()) {
            return $user->user_id === $penggunaSistem->user_id; // Hanya bisa melihat profil sendiri
        }
        
        // Super Admin bisa melihat semua pengguna
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Administrator dan Viewer hanya bisa melihat pengguna dari unit sendiri
        return $user->unit_id === $penggunaSistem->unit_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(PenggunaSistem $user): bool
    {
        // Hanya Administrator dan Super Admin yang bisa membuat pengguna baru
        return $user->isAdministrator() || $user->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(PenggunaSistem $user, PenggunaSistem $penggunaSistem): bool
    {
        // Operator hanya bisa mengupdate profil sendiri
        if ($user->isOperator()) {
            return $user->user_id === $penggunaSistem->user_id;
        }
        
        // Viewer tidak bisa mengupdate pengguna
        if ($user->isViewer()) {
            return $user->user_id === $penggunaSistem->user_id; // Hanya profil sendiri
        }
        
        // Super Admin bisa mengupdate semua pengguna
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Administrator hanya bisa mengupdate pengguna dari unit sendiri
        return $user->unit_id === $penggunaSistem->unit_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(PenggunaSistem $user, PenggunaSistem $penggunaSistem): bool
    {
        // Tidak bisa menghapus diri sendiri
        if ($user->user_id === $penggunaSistem->user_id) {
            return false;
        }
        
        // Hanya Administrator dan Super Admin yang bisa menghapus pengguna
        if (!$user->isAdministrator() && !$user->isSuperAdmin()) {
            return false;
        }
        
        // Super Admin bisa menghapus semua pengguna
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Administrator hanya bisa menghapus pengguna dari unit sendiri
        return $user->unit_id === $penggunaSistem->unit_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(PenggunaSistem $user, PenggunaSistem $penggunaSistem): bool
    {
        return $this->delete($user, $penggunaSistem);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(PenggunaSistem $user, PenggunaSistem $penggunaSistem): bool
    {
        // Hanya Super Admin yang bisa force delete
        return $user->isSuperAdmin() && $user->user_id !== $penggunaSistem->user_id;
    }

    /**
     * Determine whether the user can toggle status of the model.
     */
    public function toggleStatus(PenggunaSistem $user, PenggunaSistem $penggunaSistem): bool
    {
        // Tidak bisa mengubah status diri sendiri
        if ($user->user_id === $penggunaSistem->user_id) {
            return false;
        }
        
        // Hanya Administrator dan Super Admin yang bisa toggle status
        if (!$user->isAdministrator() && !$user->isSuperAdmin()) {
            return false;
        }
        
        // Super Admin bisa toggle status semua pengguna
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Administrator hanya bisa toggle status pengguna dari unit sendiri
        return $user->unit_id === $penggunaSistem->unit_id;
    }

    /**
     * Determine whether the user can assign roles to the model.
     */
    public function assignRoles(PenggunaSistem $user, PenggunaSistem $penggunaSistem): bool
    {
        // Hanya Administrator dan Super Admin yang bisa assign roles
        if (!$user->isAdministrator() && !$user->isSuperAdmin()) {
            return false;
        }
        
        // Super Admin bisa assign roles ke semua pengguna
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Administrator hanya bisa assign roles ke pengguna dari unit sendiri
        // dan tidak bisa assign role Super Admin
        return $user->unit_id === $penggunaSistem->unit_id;
    }

    /**
     * Determine whether the user can reset password of the model.
     */
    public function resetPassword(PenggunaSistem $user, PenggunaSistem $penggunaSistem): bool
    {
        // User bisa reset password sendiri
        if ($user->user_id === $penggunaSistem->user_id) {
            return true;
        }
        
        // Hanya Administrator dan Super Admin yang bisa reset password pengguna lain
        if (!$user->isAdministrator() && !$user->isSuperAdmin()) {
            return false;
        }
        
        // Super Admin bisa reset password semua pengguna
        if ($user->isSuperAdmin()) {
            return true;
        }
        
        // Administrator hanya bisa reset password pengguna dari unit sendiri
        return $user->unit_id === $penggunaSistem->unit_id;
    }
}