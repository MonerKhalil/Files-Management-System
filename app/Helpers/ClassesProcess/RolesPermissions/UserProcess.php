<?php

namespace App\Helpers\ClassesProcess\RolesPermissions;

use App\Helpers\ClassesBase\BaseRequest;

class UserProcess
{
    use TPermissions,TRoles;

    const GuardAPI = "api";
    const GuardWEB = "web";

    public function __construct()
    {
        $this->setRolesUserAuth();
        $this->setPermissionsUserAuth();
    }

    public function get(){
        $guard = self::GuardWEB;
        if (BaseRequest::urlIsApi(false)){
            $guard = self::GuardAPI;
        }
        return auth()->guard($guard)->user();
    }
}
