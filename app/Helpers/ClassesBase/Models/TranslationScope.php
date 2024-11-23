<?php

namespace App\Helpers\ClassesBase\Models;

use App\Helpers\MyApp;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\DB;

class TranslationScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if ($model instanceof BaseTranslationModel){
            $nameTable = $model->getTable();
            $nameTableTranslation = "{$nameTable}_translations";
            $nameTableTranslationAsCurrentLang = "{$nameTable}_translations_current";
            $nameTableTranslationAsDefaultLang = "{$nameTable}_translations_default";
            $langProcess = MyApp::Classes()->languageProcess;
            $langCurrent = $langProcess->getLanguageLocal()->id ?? null;
            $langDefault = $langProcess->getLanguageDefault()->id ?? null;
            $FkLang = $langProcess->getFkLanguageInTranslationTable();
            $FkRow = $langProcess->getFkMainTableInTranslationTable();
            $columns = $this->getColumns($model,$nameTableTranslationAsCurrentLang,$nameTableTranslationAsDefaultLang);
            $columns[] = "$nameTable.*";
            $builder->select($columns)
                ->leftJoin("{$nameTableTranslation} as {$nameTableTranslationAsCurrentLang}", function ($join) use ($nameTable,$langCurrent,$nameTableTranslationAsCurrentLang,$FkRow,$FkLang) {
                    $join->on("{$nameTable}.id", "=", "{$nameTableTranslationAsCurrentLang}.{$FkRow}")
                        ->where("{$nameTableTranslationAsCurrentLang}.{$FkLang}", $langCurrent);
                })
                ->leftJoin("{$nameTableTranslation} as {$nameTableTranslationAsDefaultLang}", function ($join) use ($nameTable,$langDefault,$nameTableTranslationAsDefaultLang,$FkRow,$FkLang) {
                    $join->on("{$nameTable}.id", "=", "{$nameTableTranslationAsDefaultLang}.{$FkRow}")
                        ->where("{$nameTableTranslationAsDefaultLang}.{$FkLang}", $langDefault);
                });
        }
    }

    private function getColumns($model, $nameTableTranslationCurrent, $nameTableTranslationDefault){
        $fieldsTranslation = $model->fieldsTranslation();
        $columns = [];
        foreach ($fieldsTranslation as $field){
            $columns[] = DB::raw("CASE WHEN {$nameTableTranslationCurrent}.{$field} IS NOT NULL THEN {$nameTableTranslationCurrent}.{$field} ELSE {$nameTableTranslationDefault}.{$field} END AS {$field}");
        }
        return $columns;
    }
}
