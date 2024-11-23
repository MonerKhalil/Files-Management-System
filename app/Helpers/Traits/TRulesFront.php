<?php

namespace App\Helpers\Traits;

use App\Helpers\ClassesBase\TypesFieldsEnum;
use App\Helpers\MyApp;

trait TRulesFront
{
    protected function fieldText(string $labelInput, bool $is_required = true, $minlength = null, $maxlength = null){
        return [
            "labelInput" => $labelInput,
            "type" => TypesFieldsEnum::TEXT->value,
            "is_required" => $is_required,
            "validation" => $this->minMaxlengthTextRule($is_required,$minlength,$maxlength),
        ];
    }

    protected function fieldNumber(string $labelInput, bool $is_required = true, $min = null, $max = null){
        return [
            "labelInput" => $labelInput,
            "type" => TypesFieldsEnum::NUMBER->value,
            "is_required" => $is_required,
            "validation" => $this->minMaxlengthTextRule($is_required,$min,$max,false),
        ];
    }

    protected function fieldEmail(string $labelInput, bool $is_required = true, $minlength = null, $maxlength = null){
        return [
            "labelInput" => $labelInput,
            "type" => TypesFieldsEnum::EMAIL->value,
            "is_required" => $is_required,
            "validation" => $this->minMaxlengthTextRule($is_required,$minlength,$maxlength),
        ];
    }

    protected function fieldEnum(array $values,string $labelInput, bool $is_required = true,string $defaultValue = null){
        return [
            "labelInput" => $labelInput,
            "type" => TypesFieldsEnum::ENUM->value,
            "is_required" => $is_required,
            "validation" => $is_required ? "required" : "",
            "values" => $values,
            "default" => $defaultValue,
        ];
    }

    protected function fieldImage(string $labelInput, bool $is_required = true,string $defaultValue = null){
        return [
            "labelInput" => $labelInput,
            "type" => TypesFieldsEnum::IMAGE->value,
            "is_required" => $is_required,
            "validation" => $is_required ? "required" : "",
            "default" => $defaultValue,
            "extinctions" => MyApp::Classes()->fileProcess->getExImages(true),
            "max_size" => MyApp::Classes()->fileProcess->getSizeImages(),//byte
        ];
    }

    protected function fieldFile(string $labelInput, bool $is_required = true,string $defaultValue = null){
        return [
            "labelInput" => $labelInput,
            "type" => TypesFieldsEnum::FILE->value,
            "is_required" => $is_required,
            "validation" => $is_required ? "required" : "",
            "default" => $defaultValue,
            "extinctions" => MyApp::Classes()->fileProcess->getExFiles(true),
            "max_size" => MyApp::Classes()->fileProcess->getSizeFiles(),//byte
        ];
    }

    protected function fieldDate(string $labelInput, bool $is_required = true,string $formatDate = "Y-m-d",string $beforeDate = null,string $afterDate = null,string $beforeFieldDate = null,string $afterFieldDate = null){
        return [
                "labelInput" => $labelInput,
                "type" => TypesFieldsEnum::DATE->value,
                "is_required" => $is_required,
                "validation" => $is_required ? "required" : "",
            ] + compact("formatDate","beforeDate",
                "beforeFieldDate","afterDate","afterFieldDate");
    }

    protected function fieldRelation(string $labelInput, string $key,string $value ,string $relation, mixed $dataRelation, bool $is_required = true){
        return [
                "labelInput" => $labelInput,
                "type" => TypesFieldsEnum::RELATION->value,
                "is_required" => $is_required,
                "validation" => $is_required ? "required" : "",
            ] + compact("key","value","relation","dataRelation");
    }

    protected function fieldEditor(string $labelInput, bool $is_required = true, $minlength = null, $maxlength = 10000){
        return [
            "labelInput" => $labelInput,
            "type" => TypesFieldsEnum::EDITOR->value,
            "is_required" => $is_required,
            "validation" => $this->minMaxlengthTextRule($is_required,$minlength,$maxlength),
        ];
    }

    protected function fieldPhone(string $labelInput, bool $is_required = true){
        return [
            "labelInput" => $labelInput,
            "type" => TypesFieldsEnum::PHONE->value,
            "is_required" => $is_required,
            "validation" => $is_required ? "required" : "",
        ];
    }

    protected function fieldPassword(string $labelInput, bool $is_required = true,int $min = 8,?int $max = null,bool $withNumber = true,bool $withSymbols = true,bool $mixedCase = true){
        return [
            "labelInput" => $labelInput,
            "type" => TypesFieldsEnum::PASSWORD->value,
            "is_required" => $is_required,
            "validation" => $is_required ? "required" : "",
        ] + compact("min","max","withNumber","withSymbols","mixedCase");
    }

    /**
     * @param $is_required
     * @param $minlength
     * @param $maxlength
     * @param bool $isLength
     * @return string
     * @author moner khalil
     */
    private function minMaxlengthTextRule($is_required , $minlength, $maxlength,bool $isLength = true): string
    {
        $length = $isLength ? "length" : "";
        $tempRule = [];
        if ($is_required){
            $tempRule[] = "required";
        }
        if (!is_null($minlength) && is_null($maxlength)) {
            $tempRule[] = "min$length=$minlength";
        } elseif (!is_null($maxlength) && is_null($minlength)) {
            $tempRule[] = "max$length=$maxlength";
        } elseif (!is_null($maxlength) && !is_null($minlength)) {
            $tempRule[] = "min$length=$minlength";
            $tempRule[] = "max$length=$maxlength";
        }elseif (!$isLength && is_null($maxlength) && is_null($minlength)) {
            $tempRule[]="min$length=1";
            $tempRule[]="max$length=255";
        }
        return implode(" ",$tempRule);
    }
}
