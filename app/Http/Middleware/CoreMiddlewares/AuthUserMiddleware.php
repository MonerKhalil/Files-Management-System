<?php

namespace App\Http\Middleware\CoreMiddlewares;

use App\Helpers\ClassesStatic\ResponseCodeTypes;
use App\Helpers\MyApp;
use App\Helpers\Traits\TResponse;
use Closure;
use Illuminate\Http\Request;

class AuthUserMiddleware
{
    use TResponse;

    public function handle(Request $request, Closure $next)
    {
        if (is_null(MyApp::Classes()->user->get())){
            return $this->responseError(__("errors.Unauthenticated"),"AuthenticationException",ResponseCodeTypes::CODE_ERROR_NOT_LOGIN,false,null,[],MyApp::RouteLogin);
        }
        return $next($request);
    }
}
