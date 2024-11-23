<?php

namespace App\Http\CrudFiles\Actions;

use App\Helpers\ClassesBase\Routes\CrudActions;
use App\Http\Controllers\CrudControllers\UserController;

class UserAction extends CrudActions
{
    protected function handle():void{
        #code...
    }

    protected function controller():string{
        return UserController::class;
    }
}
