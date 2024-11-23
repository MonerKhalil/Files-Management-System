<?php

namespace App\Http\Requests\AuthRequests;

use App\Helpers\ClassesBase\BaseRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rulePhone = $this->phoneRule(true);
        $rulePhone[] = Rule::unique("users","phone");
        return [
            "first_name" => $this->textRule(true),
            "last_name" => $this->textRule(true),
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique("users","email")],
            "phone" => $rulePhone,
            "password" => $this->passwordRule(true),
        ];
    }
}
