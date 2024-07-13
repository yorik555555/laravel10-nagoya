<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NotSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // ユーザーが有料プランに登録していないことを確認するロジック
        if ($request->user() && $request->user()->subscribed('premium_plan')) {
            return redirect('subscription/edit');
        }

        return $next($request);
    }
}
