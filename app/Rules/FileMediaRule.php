<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class FileMediaRule implements Rule
{
    private $types = null,$size = null,$canIdMediaManager;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($types,$size)
    {
        $this->types = $types;
        $this->size = $size;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if((is_file($value) || $value instanceof UploadedFile) && !is_string($value)){
            if (!in_array($value->getClientOriginalExtension(),$this->types)){
                return false;
            }
            $bytes = (double)$value->getSize() / 1024;// KB
            $mainSize = (double)$this->size / 1024;// KB
            if ($mainSize < $bytes){
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The file not ext ' . implode(",",$this->types) . " | the file is max ".$this->size." .";
    }
}
