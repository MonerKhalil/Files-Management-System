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

if (!function_exists("updateDotEnv")){
    function editFileDotEnv($key, $newValue,$withCreate = false) {
        $path = base_path('.env');
        $newValue = str_replace(" ","_",$newValue);
        if (!is_null(env($key))){
            if (is_bool(env($key))) {
                $oldValue = var_export(env($key), true);
            } else {
                $oldValue = env($key);
            }
            $newValue = (string) $newValue;
            if ($oldValue === $newValue || !is_string($newValue)) {
                return;
            }
            if (file_exists($path)) {
                file_put_contents(
                    $path, str_replace(
                        [$key.'='.$oldValue,"$key=null"],
                        [$key.'='.$newValue,$key.'='.$newValue],
                        file_get_contents($path)
                    )
                );
            }
        }elseif ($withCreate){
            if (file_exists($path)){
                $content = file_get_contents($path);
                $content .= "\n$key=$newValue\n";
                file_put_contents($path,$content);
            }
        }
    }
}
