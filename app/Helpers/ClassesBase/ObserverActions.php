<?php

namespace App\Helpers\ClassesBase;

class ObserverActions
{
    private $callable = null;

    public function __construct(callable $callable, private ?array $onlyActions = [], private ?array $exceptActions = [])
    {
        $this->callable = $callable;
    }

    public function executeCallback(string $actionCurrent){
        if (
            (is_array($this->onlyActions) && in_array($actionCurrent,$this->onlyActions))
            ||
            (is_array($this->exceptActions) && !in_array($actionCurrent,$this->exceptActions))
        ){
            $this->callMe($this->callable);
        }
    }

    private function callMe($callback){
        $callback();
    }

//    public function preGet($query):mixed{
//        return $query;
//    }
//
//    public function preCreate($data):array{
//        return $data;
//    }
//
//    public function postCreate(BaseModel $model,$data):void{
//        //code...
//    }
//
//    public function preUpdate($data):array{
//        return $data;
//    }
//
//    public function postUpdate(BaseModel $model,$data):void{
//        //code...
//    }
//
//    public function preDelete(BaseModel $model):void{
//        //code...
//    }
//
//    public function postDelete(BaseModel $model):void{
//        //code...
//    }
//
//    public function preRestore(BaseModel $model):void{
//        //code...
//    }
//
//    public function postRestore(BaseModel $model):void{
//        //code...
//    }
}
