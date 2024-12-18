<?php

namespace App\Http\Requests\CrudRequests;

use App\Helpers\ClassesBase\BaseRequest;
use Illuminate\Validation\Rule;

class UserRequest extends BaseRequest
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
            "role_id" => ["required","integer",$this->existsRow("roles","id")],
            "first_name" => $this->textRule(true),
            "last_name" => $this->textRule(true),
            "email" => !$this->isUpdatedRequest() ? array_merge($this->emailRule(),[$this->unique("users","name")]) : array_merge($this->emailRule(),[$this->unique("users","name",$id)]),
            "phone" => !$this->isUpdatedRequest() ? array_merge($this->phoneRule(true),[$this->unique("users","phone")]) : array_merge($this->phoneRule(true),[$this->unique("users","phone",$id)]),
            "password" => $this->passwordRule(true,false),
            "image" => $this->imageRule(false),
        ];
    }
}
