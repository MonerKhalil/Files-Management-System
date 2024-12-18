<?php

namespace App\Http\Requests\AuthRequests;

use App\Helpers\ClassesBase\BaseRequest;
use Illuminate\Validation\Rule;

class ResetPasswordRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "email" => ["required","email",$this->existsRow("users","email")],
            "password" => $this->passwordRule(true,true),
            "code" => $this->textRule(true),
        ];
    }
}
