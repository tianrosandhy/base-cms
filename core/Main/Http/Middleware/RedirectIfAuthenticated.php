<?php
namespace Core\Main\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{

    public function handle($request, Closure $next)
    {
        if (admin_guard()->check()) {
            return redirect(admin_url('/'));
        }

        return $next($request);
    }
}
