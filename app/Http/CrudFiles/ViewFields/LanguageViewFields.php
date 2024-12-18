<?php

namespace App\Http\CrudFiles\ViewFields;

use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\MyApp;

class LanguageViewFields extends BaseViewFields
{
    public function fieldsAllModel():array{
        return [
            "name" => $this->fieldText(__("messages.name"),true),
            "code" => $this->fieldEnum(MyApp::Classes()->languageProcess->getAllCodeLanguages(),__("messages.code"),true),
            "icon" => $this->fieldImage(__("messages.icon"),true),
            "isRTL" => $this->fieldEnum([true,false],__("messages.isRTL"),true,false),
            "default" => $this->fieldEnum([true,false],__("messages.default"),true,false),
        ];
    }

    public function fieldsSearch():array{
        return ["created_at","updated_at","name","code","default","isRTL"];
    }

    public function ignoreFieldsShow():array{
        return [];
    }

    public function ignoreFieldsCreate():array{
        return ["created_by","created_at","updated_by","updated_at"];
    }

    public function ignoreFieldsUpdate():array{
        return ["created_by","created_at","updated_by","updated_at","code"];
    }

    public function fieldsLike():array{
        return ["name"];
    }

    public function fieldsFiles():array{
        return ["icon"];
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
