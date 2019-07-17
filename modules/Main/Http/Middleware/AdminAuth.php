<?php

namespace Module\Main\Http\Middleware;

use Closure;
use Auth;
use Redirect;


class AdminAuth
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
        $user = Auth::user();
        if($request->segment(1) == "login" || $request->segment(2) == "login")
            return $next($request);

        if($user){
            //tapi kalo priviledgenya 0 ya gausa kasi
            return $next($request);
        }
        else 
            return Redirect()->intended($request->segment(1)."/login");
    }
}
