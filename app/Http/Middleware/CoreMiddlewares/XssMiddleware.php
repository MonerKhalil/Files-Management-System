<?php

namespace App\Http\Middleware\CoreMiddlewares;

use App\Helpers\ClassesStatic\ResponseCodeTypes;
use App\Helpers\MyApp;
use App\Helpers\Traits\TResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class XssMiddleware
{
    use TResponse;

    public function handle(Request $request, Closure $next)
    {
        $newRequestData = $request->all();
        foreach($newRequestData as $key => $value){
            if(is_string($value)){
                $newRequestData[$key] = MyApp::Classes()->stringProcess->xssString($value);
            }
        }
        $request->merge($newRequestData);
        return $next($request);
    }
}
