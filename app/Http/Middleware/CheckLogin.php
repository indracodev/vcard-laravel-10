<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckLogin
{
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

        return $next($request);
    }
}
