<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username|max:100',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,improvement,comodity',
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return back()->with('success', 'User berhasil ditambahkan.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|unique:users,username,' . $user->id . '|max:100',
            'role'     => 'required|in:admin,improvement,comodity',
        ]);

        $user->update([
            'username' => $request->username,
            'role'     => $request->role,
        ]);

        return back()->with('success', 'User berhasil diupdate.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password berhasil direset.');
    }

    public function destroy(User $user)
    {
     

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}