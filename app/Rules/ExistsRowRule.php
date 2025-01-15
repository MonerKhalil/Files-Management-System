<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ExistsRowRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private $table,private $key,private $callbackQuery = null)
    {
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
        $mainQuery = DB::table($this->table)
            ->where($this->key,$value)
            ->whereNull("deleted_at");
        if (!is_null($this->callbackQuery)){
            $mainQuery = $this->runCallbackQuery($this->callbackQuery,$mainQuery);
        }
        return $mainQuery->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected :attribute is invalid.';
    }

    private function runCallbackQuery($callbackQuery ,$mainQuery){
        return $callbackQuery($mainQuery);
    }
}
