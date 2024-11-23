<?php

namespace App\Helpers\Traits;

use App\Helpers\ClassesBase\Models\BaseTranslationModel;
use App\Helpers\ClassesStatic\MessagesFlash;
use App\Helpers\MyApp;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait TFunctionsCrudRepository
{
    use TLogMain;

    private function createInTranslationTable($idItem, array $translationFieldsData, $languageDifferent = null){
        $language = MyApp::Classes()->languageProcess->getLanguageDefault();
        if (!is_null($language)){
            $translationFieldsData[MyApp::Classes()->languageProcess->getFkMainTableInTranslationTable()] = $idItem;
            $translationFieldsData[MyApp::Classes()->languageProcess->getFkLanguageInTranslationTable()] = $languageDifferent->id ?? $language->id;
            $translationFieldsData["created_at"] = now();
            DB::table($this->nameTableTranslation)->insert($translationFieldsData);
        }
    }

    private function createOrUpdateInTranslationTable($idItem,array $translationFieldsData){
        $languageCode = request()->__languageCode;
        $language = MyApp::Classes()->languageProcess->getLanguageByCode($languageCode);
        $isDefault = false;

        if (is_null($language)){
            $language = MyApp::Classes()->languageProcess->getLanguageDefault();
            $isDefault = true;
        }

        if (!is_null($language)){
            $mainQuery = DB::table($this->nameTableTranslation)
                ->where(MyApp::Classes()->languageProcess->getFkMainTableInTranslationTable(),$idItem->id)
                ->where(MyApp::Classes()->languageProcess->getFkLanguageInTranslationTable(),$language->id);
            if ($mainQuery->exists()){
                $translationFieldsData['updated_at'] = now();
                $mainQuery->update($translationFieldsData);
            }elseif(!$isDefault){
                $this->createInTranslationTable($idItem,$translationFieldsData,$language);
            }
        }
    }

    private function mainEditFieldsValue($data){
        $data = $this->resolveDataSlugFields($this->resolveDatafileFields($data));
        if ($this->model instanceof BaseTranslationModel){
            $translationFields = $this->model->fieldsTranslation();
            $finalData = Arr::except($data,$translationFields);
            $finalData['@__translationFields__@'] = Arr::only($data,$translationFields);
            return $finalData;
        }
        return $data;
    }

    private function resolveDatafileFields($data){
        $keysFiles = $this->viewFields()->fieldsFiles();
        foreach ($keysFiles as $file){
            if (isset($data[$file]) && (is_file($data[$file]) || $data[$file] instanceof UploadedFile)){
                $data[$file] = MyApp::Classes()->fileProcess->storeFile($data[$file],$this->nameTable);
            }
        }
        return $data;
    }

    private function resolveDataSlugFields($data,$ignoreId = null){
        $keysSlug = $this->viewFields()->fieldsSlug();
        foreach ($keysSlug as $slug => $title){
            if (isset($data[$title])){
                $data[$slug] = MyApp::Classes()->stringProcess->uniqueColumn($data[$title],$this->queryModel()->withTrashed(),$slug,$ignoreId);
            }
        }
        return $data;
    }

    private function logAndNotify($process, $item, $showMessage){
        $this->logProcess($process,["table" => $this->nameTable,"item" => $item]);
        if ($showMessage){
            MessagesFlash::setMsgSuccess(null,$process);
        }
        #notify..
    }

    private function getMainDataInExport($values,$key,$callback){
        $dataTable = $this->get(true,true,function ($q)use ($values,$key,$callback){
            if (!is_null($callback)){
                $q = $callback($q);
            }
            if (!is_null($values)){
                $q = $q->whereIn($key,$values);
            }
            return $q;
        });
        $headTable = $this->viewFields()->getFieldsShow();
        return compact("dataTable","headTable");
    }
}
