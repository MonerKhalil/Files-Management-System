<?php

namespace App\Http\CrudFiles\Actions;

use App\Helpers\ClassesBase\Routes\CrudActions;
use App\Http\Controllers\CrudControllers\LanguageController;

class LanguageAction extends CrudActions
{
    protected function handle():void{
        #code...
    }

    protected function controller():string{
        return LanguageController::class;
    }
}
