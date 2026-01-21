<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy Configuration
    |--------------------------------------------------------------------------
    |
    | Define the allowed sources for various CSP directives.
    |
    */

    'scripts' => [
        "'self'",
        "'unsafe-inline'",
        "'unsafe-eval'",
        "https://cdn.jsdelivr.net",
        "https://code.jquery.com",
        "https://unpkg.com",
        "https://cdnjs.cloudflare.com",
    ],

    'styles' => [
        "'self'",
        "'unsafe-inline'",
        "https://fonts.googleapis.com",
        "https://cdn.jsdelivr.net",
        "https://unpkg.com",
        "https://cdnjs.cloudflare.com",
    ],

    'fonts' => [
        "'self'",
        "https://fonts.gstatic.com",
        "https://cdnjs.cloudflare.com",
        "data:",
    ],

    'images' => [
        "'self'",
        "data:",
        "https://ui-avatars.com",
    ],

    'connect' => [
        "'self'",
        "https://cdn.jsdelivr.net",
    ],

];
