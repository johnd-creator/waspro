<?php

namespace App\Policies;

use App\Models\ApplicationSetting;
use App\Models\PenggunaSistem;

class ApplicationSettingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(PenggunaSistem $penggunaSistem): bool
    {
        // Hanya Super Admin yang bisa melihat system settings
        return $penggunaSistem->isSuperAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(PenggunaSistem $penggunaSistem, ApplicationSetting $applicationSetting): bool
    {
        // Hanya Super Admin yang bisa melihat system settings
        return $penggunaSistem->isSuperAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(PenggunaSistem $penggunaSistem): bool
    {
        // Hanya Super Admin yang bisa membuat system settings
        return $penggunaSistem->isSuperAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(PenggunaSistem $penggunaSistem, ?ApplicationSetting $applicationSetting = null): bool
    {
        // Hanya Super Admin yang bisa mengupdate system settings
        return $penggunaSistem->isSuperAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(PenggunaSistem $penggunaSistem, ApplicationSetting $applicationSetting): bool
    {
        // Hanya Super Admin yang bisa menghapus system settings
        // Administrator tidak bisa menghapus untuk keamanan
        return $penggunaSistem->isSuperAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(PenggunaSistem $penggunaSistem, ApplicationSetting $applicationSetting): bool
    {
        // Hanya Super Admin yang bisa restore system settings
        return $penggunaSistem->isSuperAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(PenggunaSistem $penggunaSistem, ApplicationSetting $applicationSetting): bool
    {
        // Hanya Super Admin yang bisa force delete system settings
        return $penggunaSistem->isSuperAdmin();
    }

    /**
     * Determine whether the user can clear cache.
     */
    public function clearCache(PenggunaSistem $penggunaSistem): bool
    {
        // Hanya Super Admin yang bisa clear cache
        return $penggunaSistem->isSuperAdmin();
    }
}
