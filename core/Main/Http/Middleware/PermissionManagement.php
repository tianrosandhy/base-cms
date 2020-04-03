<?php

namespace Core\Main\Http\Middleware;

use Closure;
use Auth;
use Redirect;


class PermissionManagement
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
    	$route_name = $request->route()->getName();
    	if(empty($route_name)){
    		return $next($request);
    	}
    	else{
            //hanya validasi route name yg ada di config priviledges
            if(!in_array($route_name, all_priviledges())){
                return $next($request);
            }

            //kalau user punya akses
    		if(has_access($route_name)){
    			return $next($request);
    		}
    		else{
                //unauthorized actions utk ajax dan normal request
    			if($request->ajax()){
    				return response()->json([
    					'type' => 'error',
    					'message' => 'Unauthorized access'
    				], 403);
    			}
    			else{
    				return redirect(admin_prefix())->withErrors(['error' => 'You have no right to access this page.']);
    			}
    		}
    	}
    }
}
