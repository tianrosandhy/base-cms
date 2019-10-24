<?php
namespace Module\Main\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class ForceHttps {

    public function handle($request, Closure $next)
    {
        if (!$request->secure() && env('FORCE_HTTPS')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request); 
    }
}