<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
    private const IDLE_TIMEOUT_SECONDS = 300;

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() && Session::get('login') && Session::get('user_id')) {
            Auth::loginUsingId(Session::get('user_id'));
        }

        if (! Auth::check() && ! Session::get('login')) {
            return redirect('/login');
        }

        $lastActivity = Session::get('last_activity_at');
        if ($lastActivity && (time() - (int) $lastActivity) > self::IDLE_TIMEOUT_SECONDS && ! $request->routeIs('logout')) {
            Auth::logout();
            Session::flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['general' => ['Sesi Anda telah berakhir karena tidak ada aktivitas selama 5 menit.']],
                ], 401);
            }

            return redirect('/login')->with('error', 'Sesi Anda telah berakhir secara otomatis karena tidak ada aktivitas selama 5 menit.');
        }

        Session::put('last_activity_at', time());

        return $next($request);
    }
}
