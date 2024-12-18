<?php

namespace App\Http\Requests\CrudRequests;

use App\Helpers\ClassesBase\BaseRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $id = $this->route("id");
        return [
            "name" => !$this->isUpdatedRequest() ? array_merge($this->textRule(true),[$this->unique("roles","name")]) : array_merge($this->textRule(true),[$this->unique("roles","name",$id)]),
            "display_name" => $this->textRule(true),
            "description" => $this->editorRule(false),
            "permissions" => ["required","array","distinct"],
            "permissions.*" => ["required","integer"],
        ];
    }
}
