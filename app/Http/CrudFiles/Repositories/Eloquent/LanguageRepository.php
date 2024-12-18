<?php

namespace App\Http\CrudFiles\Repositories\Eloquent;

use App\Http\CrudFiles\Repositories\Interfaces\ILanguageRepository;
use App\Http\CrudFiles\ViewFields\LanguageViewFields;
use App\Http\CrudFiles\Actions\LanguageAction;
use App\Models\Language;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;

class LanguageRepository extends BaseRepository implements ILanguageRepository
{
    public function model(){
        return Language::class;
    }

    public function queryModel(){
        return Language::query();
    }

    public function viewFields():BaseViewFields{
        return new LanguageViewFields($this);
    }

    public function actions():CrudActions{
        return new LanguageAction($this);
    }
}
