<?php

namespace App\Http\CrudFiles\ViewFields;

use App\Helpers\ClassesBase\BaseViewFields;

class SocialMediaViewFields extends BaseViewFields
{
    public function fieldsAllModel():array{
        return [
            "name" => $this->fieldText(__("messages.name"),true),
            "url" => $this->fieldUrl(__("messages.url"),true),
            "icon" => $this->fieldImage(__("messages.icon"),true),
            "background_color" => $this->fieldText(__("messages.background_color")),
            "font_color" => $this->fieldText(__("messages.font_color")),
        ];
    }

    public function fieldsSearch():array{
        return ["created_at","updated_at","name","url"];
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
        return ["name","url"];
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
