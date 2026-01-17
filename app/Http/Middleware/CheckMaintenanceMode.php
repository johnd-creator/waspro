<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled via settings
        $isMaintenance = \App\Models\ApplicationSetting::getValue('system.maintenance_mode', false);

        if ($isMaintenance) {
            // Allow login and logout routes
            if ($request->is('login') || $request->is('logout') || $request->is('api/*')) {
                return $next($request);
            }

            // Allow Super Admin to bypass
            $user = auth()->guard('web')->user();
            if ($user && method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return $next($request);
            }

            // Abort with 503 Service Unavailable
            abort(503, 'Sistem sedang dalam mode perbaikan (Maintenance Mode). Silakan coba beberapa saat lagi.');
        }

        return $next($request);
    }
}
