<?php

namespace App\Http\Middleware\CoreMiddlewares;

use App\Helpers\ClassesStatic\ResponseCodeTypes;
use App\Helpers\MyApp;
use App\Helpers\Traits\TResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class PermissionsMiddleware
{
    use TResponse;

    public function handle(Request $request, Closure $next,string $permissions){
        return app(Pipeline::class)
            ->send($request)
            ->through([
                function ($request, $next) {
                    return app(AuthUserMiddleware::class)->handle($request, $next);
                },
            ])->then(function ($request) use ($next,$permissions){
                $permissionsOr = explode("|",$permissions);
                if (sizeof($permissionsOr) > 0){
                    if (MyApp::Classes()->user->checkPermissionExists($permissionsOr)){
                        return $next($request);
                    }
                }
                $permissionsAnd = explode("&",$permissions);
                if (sizeof($permissionsAnd) > 0){
                    if (MyApp::Classes()->user->checkPermissionExists($permissionsAnd,false)){
                        return $next($request);
                    }
                }
                return $this->responseError(__("errors.unAuthorization"),"AccessDeniedException",ResponseCodeTypes::CODE_ERROR_NOT_ACCESS,false,ResponseCodeTypes::Page_500);
            });
    }
}
