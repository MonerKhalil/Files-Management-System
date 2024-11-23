<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TextRule implements Rule
{
    private $Char = null;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->Char = ["<", ">", "$", "%", "^", "&", "|"];
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($this->Char as $str) {
            if (str_contains($value, $str)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be without these characters < > $ % ^ & |';
    }
}
