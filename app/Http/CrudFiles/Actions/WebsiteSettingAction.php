<?php

namespace App\Http\CrudFiles\Actions;

use App\Helpers\ClassesBase\Routes\CrudActions;
use App\Helpers\ClassesBase\Routes\RouteAction;
use App\Http\Controllers\CrudControllers\WebsiteSettingController;

class WebsiteSettingAction extends CrudActions
{
    public bool $isActive = false;

    protected function handle():void{
        $this->addAction(new RouteAction("show","show","showWebsiteSettings","get",["show_website_settings"]));
        $this->addAction(new RouteAction("edit","edit","editWebsiteSettings","put",["edit_website_settings"]));    }

    protected function controller():string{
        return WebsiteSettingController::class;
    }
}
