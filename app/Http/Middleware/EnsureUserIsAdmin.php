<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $isAdmin = ($user && $user->isAdmin()) || Session::get('role') === 'admin';

        if (! $isAdmin) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['general' => ['Akses ditolak. Hanya Administrator yang diizinkan melakukan aksi ini.']],
                ], 403);
            }

            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Halaman tersebut hanya untuk Administrator.');
        }

        return $next($request);
    }
}
