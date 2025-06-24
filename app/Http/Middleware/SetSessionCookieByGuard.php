<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SetSessionCookieByGuard
{
    public function handle(Request $request, Closure $next)
    {
        // Jika route prefix mengandung /admin (untuk Filament)
        if ($request->is('admin/*')) {
            Config::set('session.cookie', 'admin_session');
        }

        // Jika route prefix mengandung /pelanggan
        if ($request->is('pelanggan/*') || $request->is('booking*') || $request->is('notifikasi*')) {
            Config::set('session.cookie', 'pelanggan_session');
        }

        return $next($request);
    }
}
