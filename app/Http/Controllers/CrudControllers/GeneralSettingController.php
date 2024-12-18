<?php

namespace App\Http\Controllers\CrudControllers;

use App\Exceptions\MainException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CrudRequests\GeneralSettingRequest;
use App\Http\CrudFiles\Repositories\Interfaces\IGeneralSettingRepository;
use Illuminate\Support\Facades\DB;

class GeneralSettingController extends Controller
{
    public function __construct(private IGeneralSettingRepository $IGeneralSettingRepository)
    {
    }

    public function showGeneralSettings(){
        $generalSettings = $this->IGeneralSettingRepository->get(true,true,null,false,["category"]);
        $generalSettings = collect($generalSettings)->groupBy("category")->toArray();
        return $this->responseSuccess(compact("generalSettings"));
    }

    public function editGeneralSettings(GeneralSettingRequest $request){
        try {
            DB::beginTransaction();
            $data = $request->validated();
            foreach ($data as $key => $value){
                $temp = $this->IGeneralSettingRepository->find($key,"key",null,false,false);
                if (!is_null($temp)){
                    $temp->update([
                        "value" => $value,
                    ]);
                    if ($temp->is_env){
                        editFileDotEnv($key,$value);
                    }
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
