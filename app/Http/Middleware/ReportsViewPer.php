<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\Generalfnv;

class ReportsViewPer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'employee')
    {
		/*
            * check permission
            */ 
                $is_allow = Generalfnv::check_permission('view-reports');

                if(isset($is_allow) && $is_allow == 0) {

                    return redirect()->route('admin.permissions.permission_denied');
                    exit;
                } 
				return $next($request);
            // end permission
			
        /*if (!auth()->guard($guard)->check()) {
            $request->session()->flash('error', 'You must be an member to see this page');
            return redirect(route('admin.login'));
        }

        return $next($request);
		*/
    }
}
