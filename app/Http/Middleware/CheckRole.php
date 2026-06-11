<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan user sudah login dan punya kolom 'role' di tabel users
        if (!$request->user()) {
            return back()->with('error', 'Silakan login terlebih dahulu.');
        }

        $allowedRoles = explode('|', $role);
        if (!in_array($request->user()->role, $allowedRoles)) {
            return back()->with('error', 'Butuh role "' . $role . '" untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}