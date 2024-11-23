<?php

namespace App\Http\CrudFiles\Actions;

use App\Helpers\ClassesBase\Routes\CrudActions;
use App\Http\Controllers\CrudControllers\GroupUserController;

class GroupUserAction extends CrudActions
{
    protected function handle():void{
        #code...
    }

    protected function controller():string{
        return GroupUserController::class;
    }
}