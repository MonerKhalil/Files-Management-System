<?php

namespace App\Helpers\ClassesBase;

use App\Http\Requests\BaseRequest;
use App\Models\MediaManager;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class BaseImportData implements ToCollection, WithHeadingRow, WithValidation
{
    private $repository;
    private $rules;
    private $rulesFront;
    private $fieldsImage , $fieldsFile;

    public function __construct($repository)
    {
        $request = new BaseRequest();
        $this->repository = $repository;
        $this->rulesFront =  $this->repository->model->getFinalFieldsFrontEnd();
        $this->rules = $this->repository->model->getFinalValidationBackEnd($request);
        $this->setFieldsImageOrFile();
    }

    /**
     * @param $data
     * @param $index
     * @return array
     */
    public function prepareForValidation($data, $index): array
    {
        $temp = [];
        foreach ($data as $key => $value) {
            if (str_contains($key, "date")) {
                if (!is_numeric($value)) {
                    $temp[$key] = $value;
                } else {
                    $temp[$key] = Date::excelToDateTimeObject($value)->format('Y-m-d');
                }
            } else {
                $temp[$key] = $value;
            }
        }
        foreach ($this->fieldsImage as $value){
            $temp[$value] = MediaManager::DEFAULT_IMG;
        }
        foreach ($this->fieldsFile as $value){
            $temp[$value] = MediaManager::DEFAULT_FILE;
        }
        return $temp;
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $item) {
            $this->repository->create($item->toArray(),false);
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        $rules = [];
        if (isset($this->rules['notes'])){
            unset($this->rules['notes']);
        }
        foreach ($this->rules as $key => $rule) {
            $rules["*." . $key] = $rule;
        }
        return $rules;
    }

    private function setFieldsImageOrFile(){
        $tempFieldsImage = [];
        $tempFieldsFile = [];
        foreach ($this->rulesFront as $key => $value){
            if (is_string($value)){
                $value = explode('|' , $value);
                $tempType = $value[0];
                unset($value[0]);
                $value = $tempType;
            }
            if ($value == "file"){
                $tempFieldsFile[] = $key;
            }elseif ($value == "image"){
                $tempFieldsImage[] = $key;
            }
        }
        $this->fieldsFile = $tempFieldsFile;
        $this->fieldsImage = $tempFieldsImage;
    }
}

