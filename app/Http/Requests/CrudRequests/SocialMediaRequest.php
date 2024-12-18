<?php

namespace App\Http\Requests\CrudRequests;

use App\Helpers\ClassesBase\BaseRequest;

class SocialMediaRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "name" => $this->textRule(true),
            "url" => $this->urlRule(true),
            "icon" => $this->imageRule(true),
            "background_color" => $this->textRule(false),
            "font_color" => $this->textRule(false),
        ];
    }
}
