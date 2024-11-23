<?php

namespace App\Http\CrudFiles\ViewFields;

use App\Helpers\ClassesBase\BaseViewFields;
use App\Http\CrudFiles\Repositories\Eloquent\RoleRepository;

class UserViewFields extends BaseViewFields
{
    public function fieldsAllModel():array{
        return [
            "role_id" => $this->fieldRelation(__("messages.role"),"id","name","role",$this->getRolesData()),
            "name" => $this->fieldText(__("messages.name"),true),
            "first_name" => $this->fieldText(__("messages.first_name"),true),
            "last_name" => $this->fieldText(__("messages.last_name"),true),
            "email" => $this->fieldEmail(__("messages.email"),true),
            "phone" => $this->fieldPhone(__("messages.phone"),true),
            "password" => $this->fieldPassword(__("messages.password"),true),
            "image" => $this->fieldImage(__("messages.image"),false),
            "email_verified_at" => $this->fieldDate(__("messages.email_verified_at"),false),
        ];
    }

    public function fieldsSearch():array{
        return ["created_at","updated_at","email_verified_at","name","phone","email","role_id"];
    }

    public function ignoreFieldsShow():array{
        return ["first_name","last_name","password"];
    }

    public function ignoreFieldsCreate():array{
        return ["email_verified_at","created_by","created_at","updated_by","updated_at","name"];
    }

    public function ignoreFieldsUpdate():array{
        return ["email_verified_at","created_by","created_at","updated_by","updated_at","name"];
    }

    public function fieldsLike():array{
        return ["name","phone","email"];
    }

    public function fieldsFiles():array{
        return ["image"];
    }

    public function fieldsDates():array{
        return ["email_verified_at","created_at","updated_at"];
    }

    public function fieldsSlug(): array{
        return [
            # slug => title-field
        ];
    }

    public function getWithRelations(){
        return ["role","userCreatedBy","userUpdatedBy"];
    }

    private function getRolesData(){
        return app(RoleRepository::class)->get(true,false,function ($q){
            return $q->select(["id","name"]);
        },false);
    }
}
