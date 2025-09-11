<?php

namespace App\Providers;

use App\Models\LogPenyimpananLimbah;
use App\Models\PenggunaSistem;
use App\Models\UnitPembangkit;
use App\Policies\LogPenyimpananPolicy;
use App\Policies\PenggunaSistemPolicy;
use App\Policies\UnitPembangkitPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        LogPenyimpananLimbah::class => LogPenyimpananPolicy::class,
        PenggunaSistem::class => PenggunaSistemPolicy::class,
        UnitPembangkit::class => UnitPembangkitPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define additional gates if needed
        Gate::define('manage-master-data', function ($user) {
            return $user->isAdministrator() || $user->isSuperAdmin();
        });

        Gate::define('view-all-units', function ($user) {
            return $user->isSuperAdmin();
        });

        Gate::define('manage-system-settings', function ($user) {
            return $user->isSuperAdmin();
        });

        Gate::define('export-data', function ($user) {
            return !$user->isViewer();
        });

        Gate::define('import-data', function ($user) {
            return $user->isAdministrator() || $user->isSuperAdmin();
        });
    }
}