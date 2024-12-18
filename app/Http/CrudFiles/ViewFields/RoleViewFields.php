<?php

namespace App\Http\CrudFiles\ViewFields;

use App\Helpers\ClassesBase\BaseViewFields;

class RoleViewFields extends BaseViewFields
{
    public function fieldsAllModel():array{
        return [
            "name" => $this->fieldText(__("messages.name"),true),
            "display_name" => $this->fieldText(__("messages.display_name"),true),
            "description" => $this->fieldEditor(__("messages.description"),true),
        ];
    }

    public function fieldsSearch():array{
        return ["created_at","updated_at","name","display_name","description"];
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
        return ["name","display_name","description"];
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
        return ["userCreatedBy","userUpdatedBy"];
    }
}
