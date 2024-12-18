<?php

namespace App\Http\Requests\CrudRequests;

use App\Helpers\ClassesBase\BaseRequest;
use App\Models\GroupManager;
use Illuminate\Validation\Rule;

class GroupManagerRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "user_id" => ["required","integer",$this->existsRow("users","id")],
            "group_id" => ["nullable","integer",$this->existsRow("group_managers","id")],
            "name" => $this->textRule(true),
            "type" => ["required",Rule::in(GroupManager::TYPES)],
            "description" => $this->editorRule(false),
        ];
    }
}
