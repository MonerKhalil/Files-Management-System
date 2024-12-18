<?php

namespace App\Helpers\ClassesBase;

use Illuminate\Validation\ValidationException;

abstract class BaseDTO
{
    public function __construct($data){
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        $this->setAttributes();
    }

    public function toArray(): array {
        return get_object_vars($this);
    }

    protected abstract function setAttributes();

    /**
     * @param array $variables
     * @return void
     * @throws ValidationException
     */
    protected function isNullMulti(array $variables)
    {
        foreach ($variables as $variable => $value){
            if (is_null($value)){
                throw ValidationException::withMessages([
                    $variable => __('errors.value_failed'),
                ]);
            }
        }
    }
}
