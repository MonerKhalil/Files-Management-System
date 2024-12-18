<?php

namespace App\Http\Controllers\CrudControllers;

use App\Exceptions\MainException;
use App\Helpers\ClassesBase\TypesFieldsEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\CrudRequests\WebsiteSettingRequest;
use App\Http\CrudFiles\Repositories\Interfaces\IWebsiteSettingRepository;
use Illuminate\Support\Facades\DB;

class WebsiteSettingController extends Controller
{
    public function __construct(public IWebsiteSettingRepository $IWebsiteSettingRepository){

    }

    public function showWebsiteSettings(){
        $websiteSettings = $this->IWebsiteSettingRepository->get(true,true,null,false,["key"]);
        return $this->responseSuccess(compact("websiteSettings"));
    }

    public function editWebsiteSettings(WebsiteSettingRequest $request){
        try {
            DB::beginTransaction();
            $data = $request->validated();
            foreach ($data as $key => $value){
                $temp = $this->IWebsiteSettingRepository->find($key,"key",null,false,false);
                if (!is_null($temp)){
                    $files = [];
                    if (in_array($temp->type,[TypesFieldsEnum::FILE->value,TypesFieldsEnum::IMAGE->value])){
                        $files[] = "value";
                    }
                    $this->IWebsiteSettingRepository->update([
                        "value" => $value,
                    ],$temp->id,false,null,$files);
                }
            }
            DB::commit();
            return $this->responseSuccess();
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }
}
