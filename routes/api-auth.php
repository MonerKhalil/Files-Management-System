<?php

use App\Http\Controllers\AuthControllers\AuthController;
use App\Http\Controllers\AuthControllers\ResetPasswordUserController;
use App\Http\Controllers\AuthControllers\VerifyUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(["guest","xss","throttle:6,1"])->group(function (){
    Route::controller(AuthController::class)->group(function (){
        Route::post("register","registerUser");
        Route::post("login","login");
    });
    Route::controller(ResetPasswordUserController::class)->group(function (){
        Route::post("forget-password","forgetPassword");
        Route::post("reset-password","resetPassword");
    });
});

Route::middleware(["authUser","xss"])->prefix("auth")->group(function (){
    Route::controller(AuthController::class)->group(function (){
        Route::middleware(["verifyUser"])->group(function (){
            Route::get("user/details","getUserAuth");
            Route::put("user/change/password","changePassword");
        });
        Route::delete("logout","logout");
    });
    Route::controller(VerifyUserController::class)->middleware(["notVerifyUser"])->group(function (){
        Route::post("send/code/email/verify","sendCodeVerifyEmail")->middleware(["throttle:6,1"]);
        Route::post("code/email/verify","verifyEmailUser");
    });
});
