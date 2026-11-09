<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function showRegister()
    {
        $isAuthenticated = Auth::check() || Session::get('login');

        if (! $isAuthenticated && User::count() > 0) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mendaftarkan akun baru.');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $isAuthenticated = Auth::check() || Session::get('login');

        if (! $isAuthenticated && User::count() > 0) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Username wajib diisi.',
            'name.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Password otomatis di-hash melalui cast 'password' => 'hashed' pada model User
        $user = User::create($validated);

        if (! $isAuthenticated) {
            Auth::login($user);
            $request->session()->regenerate();
            session([
                'login' => true,
                'user_id' => $user->id,
                'username' => $user->name,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Akun pengguna "'.$user->name.'" berhasil didaftarkan!');
    }
}
