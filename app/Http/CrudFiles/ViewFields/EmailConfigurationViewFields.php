<?php

namespace App\Http\CrudFiles\ViewFields;

use App\Helpers\ClassesBase\BaseViewFields;

class EmailConfigurationViewFields extends BaseViewFields
{
    public function fieldsAllModel():array{
        return [
            #columns...
            "MAIL_MAILER" => $this->fieldText(__("messages.MAIL_MAILER"),true),
            "MAIL_HOST" => $this->fieldText(__("messages.MAIL_HOST"),true),
            "MAIL_PORT" => $this->fieldNumber(__("messages.MAIL_PORT"),true),
            "MAIL_USERNAME" => $this->fieldText(__("messages.MAIL_USERNAME"),true),
            "MAIL_PASSWORD" => $this->fieldText(__("messages.MAIL_PASSWORD"),true),
            "MAIL_FROM_ADDRESS" => $this->fieldEmail(__("messages.MAIL_FROM_ADDRESS"),true),
            "MAIL_FROM_NAME" => $this->fieldText(__("messages.MAIL_FROM_NAME"),true),
            "MAIL_ENCRYPTION" => $this->fieldText(__("messages.MAIL_ENCRYPTION"),true),
            "default" => $this->fieldBoolean(__("messages.default"),true),
        ];
    }

    public function fieldsSearch():array{
        return ["created_at","updated_at","MAIL_MAILER","MAIL_HOST","MAIL_PORT","MAIL_USERNAME","MAIL_PASSWORD","MAIL_FROM_ADDRESS","MAIL_FROM_NAME","default"];
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
        return ["MAIL_MAILER","MAIL_HOST","MAIL_PORT","MAIL_USERNAME","MAIL_PASSWORD","MAIL_FROM_ADDRESS","MAIL_FROM_NAME",];
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
