<?php

namespace App\Helpers\ClassesProcess;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CurrencyProcess
{
    const CACHE_CURRENCY_NAME = "__currencies__";
    const CURRENCY_HEADER_REQUEST_NAME = "currency_current";

    private mixed $currencyCurrent = null;

    private mixed $currencyDefault = null;

    private mixed $allCurrencies = null;

    public function __construct()
    {
        #all-currencies
        $this->allCurrencies = Cache::remember(self::CACHE_CURRENCY_NAME,86400,function (){
            try {
                return DB::table("currencies")->whereNull("deleted_at")->get();
            }catch (\Exception $exception){
                return collect([]);
            }
        });
        #default-currency
        $this->currencyDefault = $this->allCurrencies->where("default",1)->first();
        if(is_null($this->currencyDefault)){
            $this->currencyDefault = $this->allCurrencies->first();
        }
        #current-currency
        $this->currencyCurrent = $this->currencyDefault;
    }

    public function setCurrencyCurrent(string $code){
        $this->currencyCurrent = $this->getCurrencyByCode($code);
        if (is_null($this->currencyCurrent)){
            $this->currencyCurrent = $this->currencyDefault;
        }
    }

    public function getCurrencyCurrent(){
        return $this->currencyCurrent;
    }

    public function getCurrencyDefault(){
        return $this->currencyDefault;
    }

    public function getCurrencyByCode(string $code){
        return $this->allCurrencies?->where("code",$code)->first();
    }

}
