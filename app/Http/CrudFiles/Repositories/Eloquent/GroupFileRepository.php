<?php

namespace App\Http\CrudFiles\Repositories\Eloquent;

use App\Http\CrudFiles\Repositories\Interfaces\IGroupFileRepository;
use App\Http\CrudFiles\ViewFields\GroupFileViewFields;
use App\Http\CrudFiles\Actions\GroupFileAction;
use App\Models\GroupFile;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;

class GroupFileRepository extends BaseRepository implements IGroupFileRepository
{
    public function model(){
        return GroupFile::class;
    }

    public function queryModel(){
        return GroupFile::query();
    }

    public function viewFields():BaseViewFields{
        return new GroupFileViewFields($this);
    }

    public function actions():CrudActions{
        return new GroupFileAction($this);
    }
}
