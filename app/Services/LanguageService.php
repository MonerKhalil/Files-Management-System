<?php

namespace App\Services;

use App\Exceptions\CrudException;
use App\Http\CrudFiles\Repositories\Interfaces\ILanguageRepository;
use Illuminate\Support\Facades\DB;

class LanguageService
{
    public function __construct(private ILanguageRepository $ILanguageRepository)
    {
    }

    public function createLanguage($data){
        try {
            DB::beginTransaction();
            if (isset($data["default"]) && $data["default"]){
                $this->ILanguageRepository->queryModel()->update([
                    "default" => false,
                ]);
            }
            $item = $this->ILanguageRepository->create($data);
            DB::commit();
            return $item;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception->getMessage());
        }
    }

    public function updateLanguage($data,$id){
        try {
            DB::beginTransaction();
            $language = DB::table("languages")->where("id",$id)->first();
            if (is_null($language)){
                throw new \Exception(__("errors.language_not_exists"));
            }
            if (isset($data["default"]) && $data["default"]){
                $this->ILanguageRepository->queryModel()->update([
                    "default" => false,
                ]);
            }else{
                if ($language->default){
                    $temp = DB::table("languages")
                        ->where("default",1)->whereNot("id",$language->id)->first();
                    if (is_null($temp)) {
                        unset($data['default']);
                    }
                }
            }
            $item = $this->ILanguageRepository->update($data ,$id);
            DB::commit();
            return $item;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception->getMessage());
        }
    }
}
