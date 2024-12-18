<?php

namespace App\Http\CrudFiles\ViewFields;

use App\Helpers\ClassesBase\BaseViewFields;
use App\Http\CrudFiles\Repositories\Eloquent\GroupManagerRepository;
use App\Http\CrudFiles\Repositories\Eloquent\UserRepository;
use App\Models\GroupManager;

class GroupManagerViewFields extends BaseViewFields
{
    public function fieldsAllModel():array{
        return [
            "user_id" => $this->fieldRelation(__("messages.user"),"id","name","user",$this->getUserData(),true),
            "group_id" => $this->fieldRelation(__("messages.group"),"id","name","group",$this->getGroupData(),false),
            "name" => $this->fieldText(__("messages.name")),
            "type" => $this->fieldEnum(GroupManager::TYPES,__("messages.type")),
            "description" => $this->fieldEditor(__("messages.description"),false),
        ];
    }

    public function fieldsSearch():array{
        return ["created_at","updated_at","user_id","group_id","name","type"];
    }

    public function ignoreFieldsShow():array{
        return [];
    }

    public function ignoreFieldsCreate():array{
        return ["created_by","created_at","updated_by","updated_at"];
    }

    public function ignoreFieldsUpdate():array{
        return ["created_by","created_at","updated_by","updated_at"];
    }

    public function fieldsLike():array{
        return ["name"];
    }

    public function fieldsFiles():array{
        return [];
    }

    public function fieldsDates():array{
        return ["created_at","updated_at"];
    }

    public function fieldsSlug(): array{
        return [
            # slug => title-field
        ];
    }

    public function getWithRelations(){
        return ["userCreatedBy","userUpdatedBy","user_id"];
    }

    private function getUserData(){
        return app(UserRepository::class)->get(true,false,function ($q){
            return $q->select(["id","name"]);
        },false);
    }

    private function getGroupData(){
        return app(GroupManagerRepository::class)->get(true,false,function ($q){
            return $q->select(["id","name"]);
        },false);
    }
}
