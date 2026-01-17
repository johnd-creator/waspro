<?php

namespace App\Providers;

use App\Events\WasteDocumentUploaded;
use App\Listeners\SendWasteDocumentNotification;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Password;
use App\Models\ApplicationSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureRateLimiting($this->app);

        Event::listen(
            WasteDocumentUploaded::class,
            [SendWasteDocumentNotification::class, 'handle']
        );

        if (Schema::hasTable('application_settings')) {
            try {
                $minLength = ApplicationSetting::getValue('security.password_min_length', 8);
                Password::defaults(function () use ($minLength) {
                    return Password::min($minLength);
                });
            } catch (\Throwable $e) {
                // Fail silently during setup/migrations
            }
        }
    }

    /**
     * Register API rate limiting rules.
     */
    protected function configureRateLimiting(Application $app): void
    {
        $maxAttempts = (int) $app['config']->get('app.api_rate_limit', 120);
        $decaySeconds = max(1, (int) $app['config']->get('app.api_rate_limit_decay', 60));
        $decayMinutes = max(1, (int) ceil($decaySeconds / 60));

        RateLimiter::for('api', function (Request $request) use ($maxAttempts, $decayMinutes) {
            $key = $request->user()?->user_id ?? $request->ip();

            return Limit::perMinutes($decayMinutes, $maxAttempts)
                ->by($key)
                ->response(function () {
                    return response()->json([
                        'message' => 'Terlalu banyak permintaan. Silakan coba lagi beberapa saat.',
                    ], 429);
                });
        });
    }
}
