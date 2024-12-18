<?php

namespace App\Helpers\ClassesBase;

use App\Helpers\ClassesBase\Models\BaseModel;

class BaseRepositoryObserver
{
    public function preGet($query):mixed{
        return $query;
    }

    public function preCreate($data):array{
        return $data;
    }

    public function postCreate(BaseModel $model,$data):void{
        //code...
    }

    public function preUpdate($data):array{
        return $data;
    }

    public function postUpdate(BaseModel $model,$data):void{
        //code...
    }

    public function preDelete(BaseModel $model):void{
        //code...
    }

    public function postDelete(BaseModel $model):void{
        //code...
    }

    public function preRestore(BaseModel $model):void{
        //code...
    }

    public function postRestore(BaseModel $model):void{
        //code...
    }
}
