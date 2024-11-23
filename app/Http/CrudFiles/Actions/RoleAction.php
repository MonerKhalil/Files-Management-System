<?php

namespace App\Http\CrudFiles\Actions;

use App\Helpers\ClassesBase\Routes\CrudActions;
use App\Http\Controllers\CrudControllers\RoleController;

class RoleAction extends CrudActions
{
    protected function handle():void{
        #code...
        $this->showAction->setActive(false);
    }

    protected function controller():string{
        return RoleController::class;
    }
}
