<?php

namespace App\Http\Requests\CrudRequests;

use App\Helpers\ClassesBase\BaseRequest;
use App\Helpers\MyApp;
use Illuminate\Validation\Rule;

class LanguageRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules =  [
            "name" => $this->textRule(true),
            "icon" => $this->imageRule(true),
            "isRTL" => ["required","boolean"],
            "default" => ["required","boolean"],
        ];
        if (!$this->isUpdatedRequest()){
            $rules["code"] = ["required",Rule::in(MyApp::Classes()->languageProcess->getAllCodeLanguages()),Rule::unique("languages","code")];
        }
        return $rules;
    }
}
