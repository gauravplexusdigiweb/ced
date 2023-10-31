<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user()){
            if(Auth::check()){
                if (Auth::user()->user_type == 'Admin' && (Auth::user()->email_verified_at > date('Y-m-d'))) {
                    return $next($request);
                } else {
                    if(Auth::user()->email_verified_at < date('Y-m-d')){
                        return redirect(route('Admin.Expire'));
                    }else{
                        return redirect(route('Admin.Login'));
                    }
                }
            }
        }else{
            return redirect(route('Admin.Login'));
        }
        throw new HttpException(503);
    }
}
