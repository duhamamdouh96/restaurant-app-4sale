<?php

namespace App\Http\Middleware;

use App\Common\Enums\Message;
use App\Common\Response as CommonResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle($request, Closure $next, $guard = 'customer')
    {
        if (!auth()->guard($guard)->check()) {
           return (new CommonResponse)->error(Message::NOT_ALLOWED);
        }

        return $next($request);
    }
}
