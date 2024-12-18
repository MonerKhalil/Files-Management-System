<?php

namespace App\Http\CrudFiles\Actions;

use App\Helpers\ClassesBase\Routes\CrudActions;
use App\Http\Controllers\CrudControllers\UserFileController;

class UserFileAction extends CrudActions
{
    public bool $isActive = false;

    protected function handle():void{
        #code...
    }

    protected function controller():string{
        return UserFileController::class;
    }
}
