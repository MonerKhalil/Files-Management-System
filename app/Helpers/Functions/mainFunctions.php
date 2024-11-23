<?php

use App\Helpers\MyApp;
use Illuminate\Support\Facades\Session;
use App\Helpers\ClassesStatic\MessagesFlash;


if (!function_exists('filterDataRequest')){
    /**
     * @return array|mixed
     */
    function filterDataRequest(){
        return  is_array(request()->input('filter')) ? request('filter')->input('filter') : [];
    }
}

if (!function_exists('Error')){
    /**
     * @return mixed|null
     */
    function Error(){
        return Session::has(MessagesFlash::error)
            ? Session::get(MessagesFlash::error) : null;
    }
}

if (!function_exists('Success')){
    /**
     * @return mixed|null
     */
    function Success(){
        return Session::has(MessagesFlash::success)
            ? Session::get(MessagesFlash::success) : null;
    }
}

if (!function_exists('user')) {
    /**
     * @return mixed
     */
    function user(): mixed
    {
        return MyApp::Classes()->user->get();
    }
}
