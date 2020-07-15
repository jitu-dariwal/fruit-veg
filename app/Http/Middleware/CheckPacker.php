<?php

namespace App\Http\Middleware;

use Closure;

class CheckPacker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'employee')
    {
		auth()->guard($guard)->check();
		$users=auth()->guard($guard)->user();
        if($users->hasRole('packer')){
           return $next($request);
        }
           return back();
    }
}
