<?php

namespace App\Http\CrudFiles\Repositories\Eloquent;

use App\Http\CrudFiles\Repositories\Interfaces\IGeneralSettingRepository;
use App\Http\CrudFiles\ViewFields\GeneralSettingViewFields;
use App\Http\CrudFiles\Actions\GeneralSettingAction;
use App\Models\GeneralSetting;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;

class GeneralSettingRepository extends BaseRepository implements IGeneralSettingRepository
{
    public function model(){
        return GeneralSetting::class;
    }

    public function queryModel(){
        return GeneralSetting::query();
    }

    public function viewFields():BaseViewFields{
        return new GeneralSettingViewFields($this);
    }

    public function actions():CrudActions{
        return new GeneralSettingAction($this);
    }
}
