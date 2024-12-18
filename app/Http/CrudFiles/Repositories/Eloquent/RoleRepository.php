<?php

namespace App\Http\CrudFiles\Repositories\Eloquent;

use App\Http\CrudFiles\Repositories\Interfaces\IRoleRepository;
use App\Http\CrudFiles\ViewFields\RoleViewFields;
use App\Http\CrudFiles\Actions\RoleAction;
use App\Models\Role;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;
use Illuminate\Support\Facades\Cache;

class RoleRepository extends BaseRepository implements IRoleRepository
{
    public function model(){
        return Role::class;
    }

    public function queryModel(){
        return Role::query();
    }

    public function viewFields():BaseViewFields{
        return new RoleViewFields($this);
    }

    public function actions():CrudActions{
        return new RoleAction($this);
    }
}
