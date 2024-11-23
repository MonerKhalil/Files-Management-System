<?php

namespace App\Http\CrudFiles\Repositories\Eloquent;

use App\Http\CrudFiles\Repositories\Interfaces\IManagerFileRepository;
use App\Http\CrudFiles\ViewFields\ManagerFileViewFields;
use App\Http\CrudFiles\Actions\ManagerFileAction;
use App\Models\ManagerFile;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;

class ManagerFileRepository extends BaseRepository implements IManagerFileRepository
{
    public function model(){
        return ManagerFile::class;
    }

    public function queryModel(){
        return ManagerFile::query();
    }

    public function viewFields():BaseViewFields{
        return new ManagerFileViewFields($this);
    }

    public function actions():CrudActions{
        return new ManagerFileAction($this);
    }
}
