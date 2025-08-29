<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'api/*', // Exclude all API routes from CSRF verification
        'webhook/*', // Exclude webhook routes from CSRF verification
        'payment/notify', // Exclude payment notification route from CSRF verification
        'external/callback', // Exclude external callback routes from CSRF verification
        'auth/callback/*', // Exclude authentication callback routes from CSRF verification
        'admin/*', // Exclude admin routes from CSRF verification
        'user/profile/update', // Exclude user profile update route from CSRF verification
        'settings/save', // Exclude settings save route from CSRF verification
    ];
}
