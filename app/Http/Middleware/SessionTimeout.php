<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $timeout
     * @return mixed
     */
    public function handle($request, Closure $next, $timeout = 120)
    {
        if (!session()->has('lastActivityTime')) {
            session(['lastActivityTime' => now()]);
        }

        if (now()->diffInMinutes(session('lastActivityTime')) > $timeout) {
            Auth::logout();
            session()->flush();
            return redirect()->route('login')->withErrors(['message' => 'セッションがタイムアウトしました。もう一度ログインしてください。']);
        }

        session(['lastActivityTime' => now()]);

        return $next($request);
    }
}
