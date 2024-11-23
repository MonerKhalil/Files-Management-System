<?php

namespace App\Http\Middleware\CoreMiddlewares;

use App\Helpers\ClassesStatic\ResponseCodeTypes;
use App\Helpers\MyApp;
use App\Helpers\Traits\TResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class RolesMiddleware
{
    use TResponse;

    public function handle(Request $request, Closure $next,string $roles)
    {
        return app(Pipeline::class)
            ->send($request)
            ->through([
                function ($request, $next) {
                    return app(AuthUserMiddleware::class)->handle($request, $next);
                },
            ])->then(function ($request) use ($next,$roles){
                $rolesOr = explode("|",$roles);
                if (sizeof($rolesOr) > 0){
                    if (MyApp::Classes()->user->checkRoleExists($rolesOr)){
                        return $next($request);
                    }
                }
                $rolesAnd = explode("&",$roles);
                if (sizeof($rolesAnd) > 0){
                    if (MyApp::Classes()->user->checkRoleExists($rolesAnd,false)){
                        return $next($request);
                    }
                }
                return $this->responseError(__("errors.unAuthorization"),"AccessDeniedException",ResponseCodeTypes::CODE_ERROR_NOT_ACCESS,false,ResponseCodeTypes::Page_500);
            });
    }
}
