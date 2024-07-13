<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Subscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $planName
     * @return mixed
     */
    public function handle($request, Closure $next, $planName = 'premium_plan')
    {
        // ユーザーが指定されたプランに登録済みであるかを確認するロジック
        if (! $request->user() || ! $request->user()->subscribed($planName)) {
            return redirect('subscription/create');
        }

        return $next($request);
    }
}