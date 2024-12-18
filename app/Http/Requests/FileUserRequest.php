<?php

namespace App\Http\Requests;

use App\Http\Requests\CrudRequests\FileManagerRequest;

class FileUserRequest extends FileManagerRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = parent::rules();
        if (isset($rules['user_id'])){
            unset($rules['user_id']);
        }
        return $rules;
    }
}
