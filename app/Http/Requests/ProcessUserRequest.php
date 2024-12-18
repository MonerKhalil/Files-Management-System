<?php

namespace App\Http\Requests;

use App\Helpers\ClassesBase\BaseRequest;

class ProcessUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "user_ids" => ["required","array"],
            "user_ids.*" => ["required","integer"],
        ];
    }
}
