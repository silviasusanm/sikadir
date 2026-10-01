<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah user sudah login dan apakah rolenya sesuai
        if (auth()->check() && auth()->user()->role === $role) {
            return $next($request);
        }

        // 2. Jika tidak punya akses, batalkan dengan status 403 Forbidden
        abort(403, 'Akses ditolak! Anda tidak memiliki hak akses ke halaman ini.');
    }
}