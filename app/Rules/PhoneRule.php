<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;

class PhoneRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $phone = explode("-",$value);
        $code = $phone[0] ?? null;
        $phone = $phone[1] ?? null;
        if (is_null($code) || is_null($phone)){
            return false;
        }
        if (in_array($code,$this->geoLocaleCountries())){
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
        return 'The :attribute code country or phone is not validate';
    }

    private function geoLocaleCountries(){
        if (Module::has('Geolocale')){
            return Cache::remember(config("geolocale.countries_cache"),86400,function (){
                return DB::table("geolocale_countries")
                    ->select(["callingcode"])
                    ->whereNotNull("callingcode")
                    ->whereNot("callingcode","....")
                    ->whereNull("deleted_at")
                    ->get()
                    ->pluck("callingcode")->toArray();
            });
        }
        return [];
    }
}
