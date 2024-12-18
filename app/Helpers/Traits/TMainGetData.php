<?php

namespace App\Helpers\Traits;

use App\Helpers\ClassesBase\Models\BaseTranslationModel;
use App\Helpers\MyApp;

trait TMainGetData
{
    use TPaginationData;

    private function mainGetData(bool $isAll = false, bool $withFilter = true, callable $callback = null, bool $withRelations = true, array $fieldsOrders = ["created_at"], string $typeOrder = "desc",bool $isTrashed = false){
        $query = $this->queryModel();
        $query = $isTrashed ? $query->onlyTrashed() : $query;
        $query = !is_null($callback) ? $callback($query) : $query;
        $query = $withFilter ? $this->filterData($query) : $query;
        $query = (sizeof($fieldsOrders) > 0) ? $this->OrderByData($query,$fieldsOrders,$typeOrder) : $query;
        $query = $this->queryWithRelations($query,$withRelations);
        return $isAll ? $query->get() : $this->handleResponsePaginationData($this->dataPaginate($query));
        #return ($this->model instanceof BaseTranslationModel) ? AdapterData::manyDataTranslation($data) : $data;
    }

    private function OrderByData($query,array $fieldsOrders,string $typeOrder){
        foreach ($fieldsOrders as $field){
            $query = $query->orderBy($this->nameTable .".".$field,$typeOrder);
        }
        return $query;
    }

    private function filterData($query){
        $keysFilters = filterDataRequest();
        $tableName = $this->nameTable;
        $keysSearch = $this->viewFields()->getFieldsSearch();
        $keysNullable = [];
        $keysLike = $this->viewFields()->fieldsLike();
        $keysDates = [];
        $keysDateTimes = [];
        if ($this->model instanceof BaseTranslationModel){
            $tableTranslations = "{$tableName}_translations";
            $mainFKTable = MyApp::Classes()->languageProcess->getFkMainTableInTranslationTable();
            $columnNameLang = MyApp::Classes()->languageProcess->getFkLanguageInTranslationTable();
            $langCurrent = MyApp::Classes()->languageProcess->getLanguageLocal()->id ?? null;
            foreach ($keysFilters as $key => $value){
                if ( in_array($key,$keysSearch) && (in_array($key,$keysNullable) || !is_null($value)) ){
                    if (in_array($key,$keysLike)){
                        if (in_array($key,$this->model->fieldsTranslation())){
                            $query = $query->whereIn("$tableName.id",function ($q)use($tableTranslations,$mainFKTable,$columnNameLang,$langCurrent,$key,$value){
                                  $q->select(["$tableTranslations.".$mainFKTable])
                                    ->from($tableTranslations)
                                    ->where("$tableTranslations.$key","LIKE", "%$value%");
                            });
                        }else{
                            $query = $query->where("$tableName.$key" , "LIKE", "%$value%");
                        }
                    } elseif (is_array($value)){
                        if (in_array($key,$this->model->fieldsTranslation())){
                            $query = $query->whereIn("$tableName.id",function ($q)use($tableTranslations,$mainFKTable,$columnNameLang,$langCurrent,$key,$value){
                                $q->select(["$tableTranslations.".$mainFKTable])
                                    ->from($tableTranslations)
                                    ->whereIn("$tableTranslations.$key",$value);
                            });
                        }else{
                            $query = $query->whereIn("$tableName.$key",$value);
                        }
                    }else{
                        if (in_array($key,$this->model->fieldsTranslation())){
                            $query = $query->whereIn("$tableName.id",function ($q)use($tableTranslations,$mainFKTable,$columnNameLang,$langCurrent,$key,$value){
                                $q->select(["$tableTranslations.".$mainFKTable])
                                    ->from($tableTranslations)
                                    ->where("$tableTranslations.$key",$value);
                            });
                        }else{
                            $query = $query->where("$tableName.$key",$value);
                        }
                    }
                }
            }
        }else{
            foreach ($keysFilters as $key => $value){
                if ( in_array($key,$keysSearch) && (in_array($key,$keysNullable) || !is_null($value)) ){
                    if (in_array($key,$keysLike)){
                        $query = $query->where("$tableName.$key" , "LIKE", "%$value%");
                    } elseif (is_array($value)){
                        $query = $query->whereIn("$tableName.$key",$value);
                    }elseif(in_array($key,$keysDates) || in_array($key,$keysDateTimes)){
                        $query = $query->whereDate("$tableName.$key",$value);
                    }else{
                        $query = $query->where("$tableName.$key",$value);
                    }
                }
            }
        }
        $query = $this->filterDate($query,$keysDates,$keysFilters,$tableName);
        return $this->filterDate($query,$keysDateTimes,$keysFilters,$tableName,true);
    }

    private function filterDate($query,$keysDates,$keysFilters,$tableName,$isDateTime = false){
        foreach ($keysDates as $date){
            $start_date = "start_$date";
            $end_date = "end_$date";
            if (in_array($start_date,$keysFilters) && in_array($end_date,$keysFilters)){
                $start_date = MyApp::Classes()->stringProcess->DateFormat($start_date,$isDateTime);
                $end_date = MyApp::Classes()->stringProcess->DateFormat($end_date,$isDateTime,$isDateTime);
                if (is_bool($start_date) || is_bool($end_date)){
                    $start_date = null;
                    $end_date = null;
                }
                $query = $query->whereBetween("$tableName.$date", [$start_date, $end_date]);
            }
        }
        return $query;
    }

    private function queryWithRelations($query,$withRelations){
        $withRelationsArr = $this->viewFields()->getWithRelations();
        if ($withRelations && sizeof($withRelationsArr) > 0){
            $query = $query->with($withRelationsArr);
        }
        return $query;
    }
}
