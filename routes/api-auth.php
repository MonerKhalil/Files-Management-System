<?php


use App\Http\Controllers\AuthControllers\AuthController;
use App\Http\Controllers\AuthControllers\VerifyUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(["guest"])->group(function (){
    Route::controller(AuthController::class)->group(function (){
        Route::post("register","registerUser");
        Route::post("login","login");
    });
});

Route::middleware(["authUser"])->prefix("auth")->group(function (){
    Route::prefix("auth")->group(function (){
        Route::controller(AuthController::class)->group(function (){
            Route::get("user/details","getUserAuth")->middleware(["verifyUser"]);
            Route::delete("logout","logout");
        });
        Route::controller(VerifyUserController::class)->middleware(["notVerifyUser"])->group(function (){
            Route::post("send/code/email/verify","sendCodeVerifyEmail")->middleware(["throttle:6,1"]);
            Route::post("code/email/verify","verifyEmailUser");
        });
    });
});
