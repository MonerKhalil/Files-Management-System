<?php

namespace App\Http\Requests;

use App\Helpers\ClassesBase\BaseRequest;

class MoveFileRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "from_group_id" => ["required","integer"],
            "to_group_id" => ["required","integer"],
        ];
    }
}
