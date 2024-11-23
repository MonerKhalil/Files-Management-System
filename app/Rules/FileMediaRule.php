<?php

namespace App\Rules;

use App\Http\Repositories\Eloquent\MediaManagerRepository;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class FileMediaRule implements Rule
{
    private $MediaManagerRepository = null;
    private $types = null,$size = null,$canIdMediaManager;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($types,$size,bool $canIdMediaManager = true)
    {
        $this->MediaManagerRepository = (new MediaManagerRepository(app()));
        $this->types = $types;
        $this->size = $size;
        $this->canIdMediaManager = $canIdMediaManager;
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
        if (is_numeric($value) && $this->canIdMediaManager){
            return $this->checkValueIsMediaManager($value);
        }elseif((is_file($value) || $value instanceof UploadedFile) && !is_string($value)){
            if (!in_array($value->getClientOriginalExtension(),$this->types)){
                return false;
            }
            $bytes = (double)$value->getSize() / 1024;// KB
            $mainSize = (double)$this->size / 1024;// KB
            if ($mainSize < $bytes){
                return false;
            }
            return true;
        }elseif (is_string($value) && $this->canIdMediaManager){
            return $this->checkValueIsMediaManager($value,"pdf_path");
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
        return 'The id or path_file media manage is not exist | the file not ext ' . implode(",",$this->types) . " | the file is max ".$this->size." .";
    }

    private function checkValueIsMediaManager($value,string $key = "id"){
        $item = $this->MediaManagerRepository->queryModelWithActive()->where($key,$value)->first();
        if (is_null($item)){
            return false;
        }
        if (!in_array($item->extension,$this->types)){
            return false;
        }
        return true;
    }
}
