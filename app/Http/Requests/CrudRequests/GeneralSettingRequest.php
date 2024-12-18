<?php

namespace App\Http\Requests\CrudRequests;

use App\Helpers\ClassesBase\BaseRequest;
use App\Helpers\ClassesBase\TypesFieldsEnum;
use App\Http\CrudFiles\Repositories\Eloquent\GeneralSettingRepository;

class GeneralSettingRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $keys = $this->all();
        $dataKeys = $this->mainData($keys);
        $rules = [];
        foreach ($dataKeys as $objectKey){
            $rules[$objectKey->key] = match ($objectKey->type){
                TypesFieldsEnum::NUMBER->value => $this->numberRule($objectKey->is_required),
                TypesFieldsEnum::EMAIL->value => $this->emailRule($objectKey->is_required),
                TypesFieldsEnum::BOOLEAN->value => $this->boolean($objectKey->is_required),
                TypesFieldsEnum::PHONE->value => $this->phoneRule($objectKey->is_required),
                TypesFieldsEnum::DATE->value => $this->dateRule($objectKey->is_required),
                TypesFieldsEnum::URL->value => $this->urlRule($objectKey->is_required),
                TypesFieldsEnum::PASSWORD->value => $this->passwordRule($objectKey->is_required),
                default => $this->textRule()
            };
        }
        return $rules;
    }

    protected function mainData($keys){
        return app(GeneralSettingRepository::class)->get(true,false,function ($q)use($keys){
            return $q->whereIn("key",$keys);
        },false);
    }

}
