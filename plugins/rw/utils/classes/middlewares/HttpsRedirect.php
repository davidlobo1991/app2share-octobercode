<?php namespace RW\Utils\Classes\Middlewares;

use Closure;

class HttpsRedirect
{
    public function handle($request, Closure $next)
    {
        if (env('LINK_POLICY') == 'secure' && !$request->secure()) {
            return redirect()->secure($request->getRequestUri(), '301');
        }

        return $next($request);
    }
}
