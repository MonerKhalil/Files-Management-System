<?php

namespace App\Helpers\Traits;

use App\Exceptions\CrudException;
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
        $language = is_null($languageDifferent) ? MyApp::Classes()->languageProcess->getLanguageDefault() : $languageDifferent;
        if (!is_null($language)){
            $translationFieldsData[MyApp::Classes()->languageProcess->getFkMainTableInTranslationTable()] = $idItem;
            $translationFieldsData[MyApp::Classes()->languageProcess->getFkLanguageInTranslationTable()] = $language->id;
            $translationFieldsData["created_at"] = now();
            DB::table($this->nameTableTranslation)->insert($translationFieldsData);
        }
        throw new CrudException(__("errors.lang_default_is_null"));
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
                ->where(MyApp::Classes()->languageProcess->getFkMainTableInTranslationTable(),$idItem)
                ->where(MyApp::Classes()->languageProcess->getFkLanguageInTranslationTable(),$language->id);
            if ($mainQuery->exists()){
                $translationFieldsData['updated_at'] = now();
                $mainQuery->update($translationFieldsData);
            }elseif(!$isDefault){
                $this->createInTranslationTable($idItem,$translationFieldsData,$language);
            }
        }
        throw new CrudException(__("errors.lang_default_is_null"));
    }

    private function mainEditFieldsValue($data,array $fieldsFile = []){
        $data = $this->resolveDataSlugFields($this->resolveDatafileFields($data,$fieldsFile));
        if ($this->model instanceof BaseTranslationModel){
            $translationFields = $this->model->fieldsTranslation();
            $finalData = Arr::except($data,$translationFields);
            $finalData['@__translationFields__@'] = Arr::only($data,$translationFields);
            return $finalData;
        }
        return $data;
    }

    private function resolveDatafileFields($data,array $fieldsFile = []){
        $keysFiles = $this->viewFields()->fieldsFiles();
        $keysFiles = array_merge($keysFiles,$fieldsFile);
        foreach ($keysFiles as $file){
            if (isset($data[$file]) && (is_file($data[$file]) || $data[$file] instanceof UploadedFile)){
                $data[$file] = MyApp::Classes()->fileProcess->storeFile($data[$file],$this->nameTable,$this->diskStorage);
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
