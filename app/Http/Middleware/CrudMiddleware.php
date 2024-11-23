<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CrudMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        //code
        return $next($request);
    }
}
