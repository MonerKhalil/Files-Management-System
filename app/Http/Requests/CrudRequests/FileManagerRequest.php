<?php

namespace App\Http\Requests\CrudRequests;

use App\Helpers\ClassesBase\BaseRequest;
use App\Models\FileManager;

class FileManagerRequest extends BaseRequest
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
            "name" => $this->textRule(true),
            "file" => $this->fileRule(true,FileManager::getExtinction()),
            "description" => $this->editorRule(false),
        ];
    }
}
