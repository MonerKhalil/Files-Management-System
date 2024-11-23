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
    public function __construct(private $table ,private  $key ,private  $ignoreId = null,private ?array $where = null, private $callback = null)
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
        $mainData = request()->main_data;
        $q = DB::table($this->table)->where($this->key,$value);
        $callback = $this->callback;
        if (!is_null($callback)){
            $q = $callback($q);
        }
        if (!is_null($this->where) && !is_array($mainData)){
            foreach ($this->where as $key => $value){
                $q = $q->where($key,$value);
            }
        }
        if (is_array($mainData)){
            foreach ($mainData as $datum){
                $row_id = $datum["row_id"] ?? null;
                if ($datum[$this->key] == $value){
                    if (!is_null($row_id)){
                        $q = $q->whereNot("id",$row_id);
                    }
                    if (!is_null($this->where)){
                        foreach ($this->where as $key => $value){
                            $q = $q->where($key,$datum[$key]);
                        }
                    }
                    return !$q->exists();
                }
            }
        }
        return is_null($this->ignoreId) ? !$q->exists() : !$q->whereNot("id",$this->ignoreId)->exists();
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
