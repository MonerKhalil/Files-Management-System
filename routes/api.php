<?php

use App\Helpers\MyApp;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix(MyApp::VersionApi)->group(function (){
    require __DIR__ . "/api-auth.php";
    Route::middleware(["authUser","verifyUser","xss"])->group(function (){
        Route::get("dashboard/admin",[DashboardController::class,"mainDataDashboard"]);
        require __DIR__ . "/api-user.php";
    });
});
