<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        // Kalau sudah login, langsung ke dashboard masing-masing
        if (Auth::check()) {
            return match (Auth::user()->role) {
                'admin'    => redirect()->route('admin.dashboard'),
                'comodity' => redirect()->route('comodity.dashboard'),
                default    => abort(403),
            };
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ])) {
            // REGENERATE SESSION: Penting untuk keamanan & fix redirect loop
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect sesuai role
            return match ($user->role) {
                'admin'       => redirect()->route('admin.dashboard'),
                'comodity'    => redirect()->route('comodity.dashboard'),
                default       => redirect()->route('login'),
            };
        }

        return back()->withErrors([
            'login' => 'Username atau password salah'
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // BERSIHKAN SESSION: Biar gak bisa di-back
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
