<?php

namespace App\Http\CrudFiles\Repositories\Eloquent;

use App\Http\CrudFiles\Repositories\Interfaces\IWebsiteSettingRepository;
use App\Http\CrudFiles\ViewFields\WebsiteSettingViewFields;
use App\Http\CrudFiles\Actions\WebsiteSettingAction;
use App\Models\WebsiteSetting;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;

class WebsiteSettingRepository extends BaseRepository implements IWebsiteSettingRepository
{
    public function model(){
        return WebsiteSetting::class;
    }

    public function queryModel(){
        return WebsiteSetting::query();
    }

    public function viewFields():BaseViewFields{
        return new WebsiteSettingViewFields($this);
    }

    public function actions():CrudActions{
        return new WebsiteSettingAction($this);
    }
}
