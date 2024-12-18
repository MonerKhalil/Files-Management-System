<?php

namespace App\Http\CrudFiles\Repositories\Eloquent;

use App\Http\CrudFiles\Repositories\Interfaces\ISocialMediaRepository;
use App\Http\CrudFiles\ViewFields\SocialMediaViewFields;
use App\Http\CrudFiles\Actions\SocialMediaAction;
use App\Models\SocialMedia;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;

class SocialMediaRepository extends BaseRepository implements ISocialMediaRepository
{
    public function model(){
        return SocialMedia::class;
    }

    public function queryModel(){
        return SocialMedia::query();
    }

    public function viewFields():BaseViewFields{
        return new SocialMediaViewFields($this);
    }

    public function actions():CrudActions{
        return new SocialMediaAction($this);
    }
}
