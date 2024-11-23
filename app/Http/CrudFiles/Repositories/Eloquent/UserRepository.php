<?php

namespace App\Http\CrudFiles\Repositories\Eloquent;

use App\Http\CrudFiles\Repositories\Interfaces\IUserRepository;
use App\Http\CrudFiles\ViewFields\UserViewFields;
use App\Http\CrudFiles\Actions\UserAction;
use App\Models\User;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;

class UserRepository extends BaseRepository implements IUserRepository
{
    public function model(){
        return User::class;
    }

    public function queryModel(){
        return User::query();
    }

    public function viewFields():BaseViewFields{
        return new UserViewFields($this);
    }

    public function actions():CrudActions{
        return new UserAction($this);
    }
}
