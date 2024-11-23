<?php

namespace App\Http\CrudFiles\Repositories\Eloquent;

use App\Http\CrudFiles\Repositories\Interfaces\IGroupUserRepository;
use App\Http\CrudFiles\ViewFields\GroupUserViewFields;
use App\Http\CrudFiles\Actions\GroupUserAction;
use App\Models\GroupUser;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;

class GroupUserRepository extends BaseRepository implements IGroupUserRepository
{
    public function model(){
        return GroupUser::class;
    }

    public function queryModel(){
        return GroupUser::query();
    }

    public function viewFields():BaseViewFields{
        return new GroupUserViewFields($this);
    }

    public function actions():CrudActions{
        return new GroupUserAction($this);
    }
}
