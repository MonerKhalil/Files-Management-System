<?php

namespace App\Http\CrudFiles\ViewFields;

use App\Helpers\ClassesBase\BaseViewFields;
use App\Http\CrudFiles\Repositories\Eloquent\UserRepository;
use App\Models\FileManager;

class FileManagerViewFields extends BaseViewFields
{
    public function fieldsAllModel():array{
        return [
            #columns...
            "user_id" => $this->fieldRelation(__("messages.user"),"id","name","user",$this->userData(),true),
            "name" => $this->fieldText(__("messages.name")),
            "name_default" => $this->fieldText(__("messages.file_name_default")),
            "type" => $this->fieldEnum(["file","image"],__("messages.type")),
            "extinction" => $this->fieldText(__("messages.extinction")),
            "size" => $this->fieldNumber(__("messages.size")),
            "description" => $this->fieldEditor(__("messages.description"),false),
            "file" => $this->fieldFile(__("file"),true,null,FileManager::getExtinction()),
        ];
    }

    public function fieldsSearch():array{
        return ["created_at","updated_at","user_id","name","name_default","type","extinction","size"];
    }

    public function ignoreFieldsShow():array{
        return [];
    }

    public function ignoreFieldsCreate():array{
        return ["created_by","created_at","updated_by","updated_at","name_default","type","extinction","size"];
    }

    public function ignoreFieldsUpdate():array{
        return ["created_by","created_at","updated_by","updated_at","name_default","type","extinction","size"];
    }

    public function fieldsLike():array{
        return ["name","name_default",];
    }

    public function fieldsFiles():array{
        return ["file"];
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
        return ["userCreatedBy","userUpdatedBy","user"];
    }

    private function getUserData(){
        return app(UserRepository::class)->get(true,false,function ($q){
            return $q->select(["id","name"]);
        },false);
    }
}
