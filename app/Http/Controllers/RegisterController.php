<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function showRegister()
    {
        $userCount = User::count();
        $isAuthenticated = Auth::check() || Session::get('login');

        if (! $isAuthenticated && $userCount > 0) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk mendaftarkan akun baru.');
        }

        if ($isAuthenticated) {
            $user = Auth::user();
            $isAdmin = ($user && $user->isAdmin()) || Session::get('role') === 'admin';

            if (! $isAdmin) {
                return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya Administrator yang dapat mendaftarkan akun baru.');
            }

            return redirect()->route('users.index');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $userCount = User::count();
        $isAuthenticated = Auth::check() || Session::get('login');

        if (! $isAuthenticated && $userCount > 0) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Silakan login terlebih dahulu.');
        }

        if ($isAuthenticated) {
            $currentUser = Auth::user();
            $isAdmin = ($currentUser && $currentUser->isAdmin()) || Session::get('role') === 'admin';

            if (! $isAdmin) {
                return redirect()->route('dashboard')->with('error', 'Akses ditolak. Hanya Administrator yang dapat mendaftarkan akun baru.');
            }
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

        $validated['role'] = $userCount === 0 ? UserRole::Admin->value : UserRole::Hcs->value;

        $user = User::create($validated);

        if (! $isAuthenticated) {
            Auth::login($user);
            $request->session()->regenerate();
            session([
                'login' => true,
                'user_id' => $user->id,
                'username' => $user->name,
                'role' => $user->role->value,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Akun pengguna "'.$user->name.'" berhasil didaftarkan!');
    }
}
