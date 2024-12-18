<?php

namespace App\Http\CrudFiles\Actions;

use App\Helpers\ClassesBase\Routes\CrudActions;
use App\Http\Controllers\CrudControllers\FileManagerController;

class FileManagerAction extends CrudActions
{
    protected function handle():void{
        #code...
        $this->exportXLSXAction->setActive(false);
        $this->exportPDFAction->setActive(false);
    }

    protected function controller():string{
        return FileManagerController::class;
    }
}
