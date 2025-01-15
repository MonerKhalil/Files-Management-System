<?php

namespace App\Http\CrudFiles\Repositories\Eloquent;

use App\DTO\UserDTO;
use App\Http\CrudFiles\Repositories\Interfaces\IUserFileRepository;
use App\Http\CrudFiles\ViewFields\UserFileViewFields;
use App\Http\CrudFiles\Actions\UserFileAction;
use App\Models\UserFile;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;

class UserFileRepository extends BaseRepository implements IUserFileRepository
{
    public function model(){
        return UserFile::class;
    }

    public function queryModel(){
        return UserFile::query();
    }

    public function viewFields():BaseViewFields{
        return new UserFileViewFields($this);
    }

    public function actions():CrudActions{
        return new UserFileAction($this);
    }
}
