<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
//use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SessionTimeout
{
    public function handle($request, Closure $next)
    {
        $maxIdleTime = config('session.lifetime') * 60; // seconds

        // Check if the user is authenticated and session exists
        if (Auth::check() && $request->session()->exists('lastActivityTime')) {
            $lastActivityTime = $request->session()->get('lastActivityTime');

            // Check if session is expired
            if (time() - strtotime($lastActivityTime) > $maxIdleTime) {
                // Log out the user
                Auth::logout();

                // Invalidate the session
                $request->session()->invalidate();

                // Redirect to login or any other page as needed
                return redirect()->route('login')->with('error', 'セッションがタイムアウトしました。再度ログインしてください。');
            }
        }

        // Update last activity time in session
        $request->session()->put('lastActivityTime', Carbon::now());

        return $next($request);
    }
}
