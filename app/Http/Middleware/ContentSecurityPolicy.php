<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentSecurityPolicy
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // -- Base Allowed Domains from Config --
        $scripts = config('csp.scripts', []);
        $styles = config('csp.styles', []);
        $fonts = config('csp.fonts', []);
        $images = config('csp.images', []);
        $connect = config('csp.connect', []);

        // -- Development Environment (Vite) --
        if (app()->isLocal()) {
            $devUrls = [
                "http://localhost:5173",
                "http://127.0.0.1:5173",
                "ws://localhost:5173",
                "ws://127.0.0.1:5173",
            ];

            // Add Vite URLs to relevant directives
            $scripts = array_merge($scripts, $devUrls);
            $styles = array_merge($styles, $devUrls);
            $connect = array_merge($connect, $devUrls);
            // Vite sometimes loads assets/images from the dev server
            $images = array_merge($images, $devUrls);
        }

        // -- Build Policy String --
        $policy = [
            "default-src 'self'",
            "script-src " . implode(' ', $scripts),
            "style-src " . implode(' ', $styles),
            "font-src " . implode(' ', $fonts),
            "img-src " . implode(' ', $images),
            "connect-src " . implode(' ', $connect),
            "frame-src 'self'",
            "object-src 'none'",
        ];

        $response->headers->set('Content-Security-Policy', implode('; ', $policy) . ';');

        return $response;
    }
}
