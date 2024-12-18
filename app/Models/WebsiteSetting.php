<?php

namespace App\Models;

use App\Helpers\ClassesBase\Models\BaseTranslationModel;
use App\Models\Translations\WebsiteSettingTranslation;

class WebsiteSetting extends BaseTranslationModel
{
    const NAME_CACHE = "__WEBSITE_SETTING__";

    public function fieldsTranslation(): array{
        return [
            "value",
        ];
    }

    #-Relations-Functions-------

    public function translations(){
        return $this->hasMany(WebsiteSettingTranslation::class ,"row_main_id","id");
    }
}
