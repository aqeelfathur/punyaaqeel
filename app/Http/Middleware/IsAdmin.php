<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {  // Ganti isAdmin menjadi is_admin
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}