<?php

namespace App\Helpers\ClassesBase;

use Illuminate\Support\Arr;
use App\Helpers\Traits\TRulesFront;
use App\Helpers\ClassesBase\Models\BaseTranslationModel;
use App\Helpers\ClassesBase\Repositories\IBaseRepository;

abstract class BaseViewFields
{
    use TRulesFront;

    private mixed $finalFields = null;

    public function __construct(private IBaseRepository $repository)
    {
    }

    public abstract function fieldsAllModel():array;

    public abstract function ignoreFieldsShow():array;

    public abstract function fieldsSearch():array;

    public abstract function ignoreFieldsCreate():array;

    public abstract function ignoreFieldsUpdate():array;

    public abstract function fieldsLike():array;

    public abstract function fieldsFiles():array;

    public abstract function fieldsDates():array;

    public abstract function fieldsSlug():array;

    public function finalFields(){
        if (is_null($this->finalFields)){
            $fields = array_merge($this->fieldsAllModel() , $this->mainColumns());
            $model = $this->repository->model;
            if ($model instanceof BaseTranslationModel){
                $fieldsTranslation = $model->fieldsTranslation();
                foreach ($fields as $field => $types){
                    if (in_array($field,$fieldsTranslation)){
                        $fields[$field]["is_translation"] = true;
                    }
                }
            }
            $this->finalFields = $fields;
        }
        return $this->finalFields ?? [];
    }

    public function getFieldsShow(){
        return Arr::except($this->finalFields(),$this->ignoreFieldsShow());
    }

    public function getFieldsSearch(){
        return Arr::only($this->finalFields(),$this->fieldsSearch());
    }

    public function getFieldsCreate(){
        return Arr::except($this->finalFields(),$this->ignoreFieldsCreate());
    }

    public function getFieldsUpdate(){
        return Arr::except($this->finalFields(),$this->ignoreFieldsUpdate());
    }

    public function getWithRelations(){
        return [];
    }

    private function mainColumns(){
        return [
            "created_by" => $this->fieldRelation(__("messages.created_by"),"id","name","userCreatedBy",null,false),
            "created_at" => $this->fieldDate(__("messages.created_at"),false),
            "updated_by" => $this->fieldRelation(__("messages.updated_by"),"id","name","userUpdatedBy",null,false),
            "updated_at" => $this->fieldDate(__("messages.updated_at"),false),
        ];
    }
}
