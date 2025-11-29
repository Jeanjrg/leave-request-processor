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
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Ambil role user saat ini
        $userRole = Auth::user()->role;
        $requiredRoles = explode('|', $role); // Mendukung multiple roles: 'Admin|HRD'

        // Cek apakah role user ada dalam list role yang dibutuhkan
        if (!in_array($userRole, $requiredRoles)) {
            // Jika tidak sesuai, arahkan ke halaman home atau beri error 403
            return abort(403, 'Akses Ditolak. Role Anda tidak diizinkan mengakses halaman ini.');
        }

        return $next($request);
    }
}
