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
        if (!$request->user() || $request->user()->role !== $role) {
            // Jika role tidak sesuai: jangan abort 403.
            // Tetap di halaman user (back) dan munculkan pesan butuh role tertentu.
            return back()->with('error', 'Butuh role "' . $role . '" untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}