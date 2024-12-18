<?php

namespace App\Http\CrudFiles\Actions;

use App\Helpers\ClassesBase\Routes\CrudActions;
use App\Http\Controllers\CrudControllers\GroupManagerController;

class GroupManagerAction extends CrudActions
{
    protected function handle():void{
        #code...
    }

    protected function controller():string{
        return GroupManagerController::class;
    }
}
