<?php

namespace App\Http\Requests;

use App\Helpers\ClassesBase\BaseRequest;

class ProcessFileRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            "file_ids" => ["required","array"],
            "file_ids.*" => ["required","integer"],
        ];
    }
}
