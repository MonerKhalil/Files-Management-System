<?php

namespace App\DTO;

use App\Helpers\ClassesBase\BaseDTO;
use App\Helpers\MyApp;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManagerDTO extends BaseDTO
{
    public $file;
    public $name,
            $name_default,
            $type,
            $extinction,
            $size,
            $description,
            $user_id;

    protected function setAttributes()
    {
        $vars = ["name","file"];
        $this->isNullMulti($vars);
        $file = $this->file;
        if (!($file instanceof UploadedFile) || !is_file($file)){
            throw ValidationException::withMessages([
                "file" => __('errors.file_not_valid'),
            ]);
        }
        $this->name_default = $file->getClientOriginalName();
        $this->size = $file->getSize();
        $this->extinction = $file->getClientOriginalExtension();
        $this->type = in_array($file->getClientOriginalExtension(),MyApp::Classes()->fileProcess->getExImages(true)) ? "image" : "file";
        if (is_null($this->user_id)){
            $this->user_id = MyApp::Classes()->user->get()->id;
        }
    }
}
