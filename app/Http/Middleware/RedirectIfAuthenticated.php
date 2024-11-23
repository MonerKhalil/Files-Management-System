<?php

namespace App\Http\Middleware;

use App\Helpers\ClassesStatic\ResponseCodeTypes;
use App\Helpers\MyApp;
use App\Helpers\Traits\TResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    use TResponse;

    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return $this->responseError(__("errors.userIsGuest"),"GuestException",ResponseCodeTypes::CODE_ERROR_BAD_REQUEST,false,null,[],MyApp::RouteDashBoard);
            }
        }

        return $next($request);
    }
}
