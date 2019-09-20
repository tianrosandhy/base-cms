<?php
namespace Module\Main\Http\Middleware;

use Closure;

class SecurityHeader
{
	public function handle($request, Closure $next)
    {	
        $response = $next($request);

    	$response->headers->set('X-Frame-Options', 'DENY');
    	$response->headers->set('X-Xss-Protection', '1; mode=block');
    	$response->headers->set('Content-Security-Policy', "default-src 'self' https:; img-src 'self' data: https:; style-src 'self' 'unsafe-inline' https: http://maxcdn.bootstrapcdn.com http://cdnjs.cloudflare.com; font-src 'self' http: https://fonts.gstatic.com https://use.fontawesome.com; script-src 'self' 'unsafe-inline' 'unsafe-eval' https: http://cdnjs.cloudflare.com http://maxcdn.bootstrapcdn.com; connect-src 'self' https:");
    	$response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
    	$response->headers->set('Feature-Policy', "fullscreen *, payment 'none' ");
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('X-Powered-By', 'TianRosandhy');

    	return $response;
    }
}