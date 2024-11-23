<?php

namespace App\Http\CrudFiles\Repositories\Eloquent;

use App\Http\CrudFiles\Repositories\Interfaces\IFileUserRepository;
use App\Http\CrudFiles\ViewFields\FileUserViewFields;
use App\Http\CrudFiles\Actions\FileUserAction;
use App\Models\FileUser;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;

class FileUserRepository extends BaseRepository implements IFileUserRepository
{
    public function model(){
        return FileUser::class;
    }

    public function queryModel(){
        return FileUser::query();
    }

    public function viewFields():BaseViewFields{
        return new FileUserViewFields($this);
    }

    public function actions():CrudActions{
        return new FileUserAction($this);
    }
}
