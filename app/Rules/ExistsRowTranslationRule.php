<?php

namespace App\Rules;

use App\Helpers\MyApp;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ExistsRowTranslationRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(private $mainTable,
                                private $keyTranslation,
                                private $callbackQueryMainTable = null,
                                private $callbackQueryTranslationTable = null)
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
        $mainTable = $this->mainTable;
        $mainQuery = DB::table($mainTable)
            ->whereNull("deleted_at");
        if (!is_null($this->callbackQueryMainTable)){
            $mainQuery = $this->runCallbackQuery($this->callbackQuery,$mainQuery);
        }
        $mainQuery = $mainQuery->whereIn("id",function ($q)use($mainTable,$value){
            $tableTranslation = "{$mainTable}_translations";
            $fk = MyApp::Classes()->languageProcess->getFkMainTableInTranslationTable();
            $q = $q->select("{$tableTranslation}.{$fk}")->from($tableTranslation)
                ->where($this->keyTranslation);
            if (!is_null($this->callbackQueryTranslationTable)){
                $q = $this->runCallbackQuery($this->callbackQueryTranslationTable,$q);
            }
            return $q;
        });
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
