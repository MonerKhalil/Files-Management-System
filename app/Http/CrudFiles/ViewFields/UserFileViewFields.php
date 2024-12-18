<?php

namespace App\Http\CrudFiles\ViewFields;

use App\Helpers\ClassesBase\BaseViewFields;

class UserFileViewFields extends BaseViewFields
{
    public function fieldsAllModel():array{
        return [
            #columns...
        ];
    }

    public function fieldsSearch():array{
        return ["created_at","updated_at"];
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
        return [];
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
