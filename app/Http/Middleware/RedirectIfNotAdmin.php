<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RedirectIfNotAdmin
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
		if (!auth()->guard('employee')->check()) {
			$request->session()->flash('error', 'You must be an admin to see this page');
            return redirect(route('admin'));
		}
        return $next($request);
    }
}
