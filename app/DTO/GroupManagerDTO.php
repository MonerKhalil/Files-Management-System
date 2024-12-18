<?php

namespace App\DTO;

use App\Helpers\ClassesBase\BaseDTO;
use App\Helpers\MyApp;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GroupManagerDTO extends BaseDTO
{
    public $name,
           $type,
           $description,
           $group_id,
           $url_generate,
           $user_id;

    protected function setAttributes()
    {
        $vars = ["name","type"];
        $this->isNullMulti($vars);
        if (is_null($this->user_id)){
            $this->user_id = MyApp::Classes()->user->get()->id;
        }
    }
}
