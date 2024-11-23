<?php

use App\Helpers\MyApp;
use Illuminate\Support\Facades\Route;

Route::prefix(MyApp::VersionApi)->group(function (){
    require __DIR__ . "/api-auth.php";
});
