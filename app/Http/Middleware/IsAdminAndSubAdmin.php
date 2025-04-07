<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdminAndSubAdmin
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
        if(Auth::check())
        {
            if(Auth::user()->role == 1 || Auth::user()->role == 2)
            {
                if(Auth::user()->role == 1)
                {
                    return $next($request);
                }

                if(Auth::user()->role == 2)
                {
                    if(Auth::user()->status == 1)
                    {
                        return $next($request);
                    }else{
                        Auth::logout();
                        return redirect('/')->with('error', 'Account Inactive, Please Contact Your Admin!');
                    }
                }
            }
            else
            {
                return redirect('/dashboard')->with('error', 'Invalid Access!');
            }
        }
        else
        {
            return redirect('/')->with('error', 'Please login');
        }
    }
    
}
