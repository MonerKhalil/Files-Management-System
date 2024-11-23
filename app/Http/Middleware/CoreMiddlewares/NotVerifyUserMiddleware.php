<?php

namespace App\Http\Middleware\CoreMiddlewares;

use App\Helpers\ClassesStatic\ResponseCodeTypes;
use App\Helpers\MyApp;
use App\Helpers\Traits\TResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class NotVerifyUserMiddleware
{
    use TResponse;

    public function handle(Request $request, Closure $next)
    {
        return app(Pipeline::class)
            ->send($request)
            ->through([
                function ($request, $next) {
                    return app(AuthUserMiddleware::class)->handle($request, $next);
                },
            ])->then(function ($request) use ($next){
                $user = MyApp::Classes()->user->get();
                if (is_null($user->email_verified_at)){
                    return $next($request);
                }
                return $this->responseError(__("errors.userIsVerified"),"UserIsVerifiedException");
            });
    }
}
