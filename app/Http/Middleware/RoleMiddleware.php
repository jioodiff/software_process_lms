<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman tersebut.');
        }

        if (!in_array(auth()->user()->role, $roles)) {
            $dashboardRoute = auth()->user()->isAdmin() ? 'admin.dashboard' : 'mahasiswa.dashboard';
            return redirect()->route($dashboardRoute)->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        }

        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        return $next($request);
    }
}
