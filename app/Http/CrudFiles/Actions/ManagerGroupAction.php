<?php

namespace App\Http\CrudFiles\Actions;

use App\Helpers\ClassesBase\Routes\CrudActions;
use App\Http\Controllers\CrudControllers\ManagerGroupController;

class ManagerGroupAction extends CrudActions
{
    protected function handle():void{
        #code...
    }

    protected function controller():string{
        return ManagerGroupController::class;
    }
}
