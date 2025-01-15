<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private $table ,private  $key ,private  $ignoreId = null, private $callback = null)
    {
        //
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
        $mainQuery = DB::table($this->table)->where($this->key,$value);
        $callback = $this->callback;
        if (!is_null($callback)){
            $mainQuery = $callback($mainQuery);
        }
        if (!is_null($this->ignoreId)){
            $mainQuery = $mainQuery->whereNot("id",$this->ignoreId);
        }
        return !$mainQuery->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The :attribute has already been taken.";
    }
}
