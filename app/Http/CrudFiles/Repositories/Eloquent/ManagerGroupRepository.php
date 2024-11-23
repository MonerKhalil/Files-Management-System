<?php

namespace App\Http\CrudFiles\Repositories\Eloquent;

use App\Http\CrudFiles\Repositories\Interfaces\IManagerGroupRepository;
use App\Http\CrudFiles\ViewFields\ManagerGroupViewFields;
use App\Http\CrudFiles\Actions\ManagerGroupAction;
use App\Models\ManagerGroup;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;

class ManagerGroupRepository extends BaseRepository implements IManagerGroupRepository
{
    public function model(){
        return ManagerGroup::class;
    }

    public function queryModel(){
        return ManagerGroup::query();
    }

    public function viewFields():BaseViewFields{
        return new ManagerGroupViewFields($this);
    }

    public function actions():CrudActions{
        return new ManagerGroupAction($this);
    }
}
