<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() || Session::get('login')) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (! Auth::attempt(['name' => $credentials['username'], 'password' => $credentials['password']])) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Username atau password salah.');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        Session::put('login', true);
        Session::put('user_id', $user->id);
        Session::put('username', $user->name);
        Session::put('role', $user->role->value);
        Session::put('last_activity_at', time());

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        $isIdle = $request->boolean('idle');

        Auth::logout();
        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($isIdle) {
            return redirect('/login')->with('error', 'Sesi Anda telah berakhir secara otomatis karena tidak ada aktivitas selama 5 menit.');
        }

        return redirect('/login');
    }
}
