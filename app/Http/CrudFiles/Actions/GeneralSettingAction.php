<?php

namespace App\Http\CrudFiles\Actions;

use App\Helpers\ClassesBase\Routes\CrudActions;
use App\Helpers\ClassesBase\Routes\RouteAction;
use App\Http\Controllers\CrudControllers\GeneralSettingController;

class GeneralSettingAction extends CrudActions
{
    public bool $isActive = false;

    protected function handle():void{
        $this->addAction(new RouteAction("show","show","showGeneralSettings","get",["show_general_settings"]));
        $this->addAction(new RouteAction("edit","edit","editGeneralSettings","put",["edit_general_settings"]));
    }

    protected function controller():string{
        return GeneralSettingController::class;
    }
}
