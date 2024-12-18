<?php

namespace App\Http\Requests;

use App\Helpers\ClassesBase\BaseRequest;

class ProcessGroupRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "group_ids" => ["required","array"],
            "group_ids.*" => ["required","integer"],
        ];
    }
}
