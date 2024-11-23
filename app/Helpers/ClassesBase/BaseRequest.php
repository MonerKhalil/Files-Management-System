<?php

namespace App\Helpers\ClassesBase;

use App\Helpers\MyApp;
use App\Rules\FileMediaRule;
use App\Rules\PhoneRule;
use App\Rules\TextRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isUpdatedRequest(): bool
    {
        $Final = false;
        $routeName = is_null($this->route()) ? "" : $this->route()->getName();
        if (!is_null($routeName)){
            $Final = is_numeric(strpos($routeName, "update")) || is_numeric(strpos($routeName, "edit"));
        }
        return request()->isMethod("PUT") || request()->isMethod("PATCH") || $Final;
    }

    /**
     * @param bool $isRequired
     * @param bool $withIcon
     * @param bool $canIdMediaManager
     * @return array
     * @author moner khalil
     */
    public function imageRule(bool $isRequired = true, bool $withIcon = false, bool $canIdMediaManager = true): array
    {
        $rule = $isRequired ? 'required' : 'nullable';
        $exs = MyApp::Classes()->fileProcess->getExImages(true);
        if ($withIcon){
            $exs[] = "icon";
        }
        return [$rule,new FileMediaRule($exs,MyApp::Classes()->fileProcess->getSizeImages(),$canIdMediaManager)];
    }

    /**
     * @param bool $isRequired
     * @param bool $canIdMediaManager
     * @return array
     * @author moner khalil
     */
    public function fileRule(bool $isRequired = true, bool $canIdMediaManager = true): array
    {
        return [$isRequired ? 'required' : 'nullable',new FileMediaRule(MyApp::Classes()->fileProcess->getExFiles(true), MyApp::Classes()->fileProcess->getSizeFiles(),$canIdMediaManager)];
    }

    /**
     * @param bool $isRequired
     * @return string[]
     * @author moner khalil
     */
    public function dateRule(bool $isRequired = true){
        return [$isRequired ? 'required' : 'nullable',"date_format:Y-m-d"];
    }

    /**
     * @description string without in TextRule Class
     * add char.. /-
     * @param bool $isRequired
     * @param null $min
     * @param null $max
     * @return array
     * @author moner khalil
     */
    public function textRule(bool $isRequired = true, $min = null, $max = null): array
    {
        $temp_rules = [];
        $temp_rules[] = $isRequired ? 'required' : 'nullable';
        $temp_rules[] = "string";
        $temp_rules[] = new TextRule();
        return $this->minMaxStrRule($temp_rules, $min, $max);
    }

    /**
     * @description string without in TextRule Class
     * @param bool|null $isRequired
     * @param null $min
     * @param int $max
     * @return array
     * @author moner khalil
     */
    public function editorRule(bool $isRequired = null, $min = null, $max = 10000): array
    {
        $temp_rules = [];
        $temp_rules[] = $isRequired ? 'required' : 'nullable';
        $temp_rules[] = "string";
        return $this->minMaxStrRule($temp_rules, $min, $max);
    }

    /**
     * @param array $tempRule
     * @param $min
     * @param $max
     * @return array
     * @author moner khalil
     */
    private function minMaxStrRule(array $tempRule, $min, $max): array
    {
        if (!is_null($min) && is_null($max)) {
            $tempRule[] = "min:" . $min;
        } elseif (!is_null($max) && is_null($min)) {
            $tempRule[] = "max:" . $max;
        } elseif (!is_null($max) && !is_null($min)) {
            $tempRule[] = "min:" . $min;
            $tempRule[] = "max:" . $max;
        }else{
            $tempRule[]="min:1";
            $tempRule[]="max:255";
        }
        return $tempRule;
    }

    /**
     * @param bool $isRequired
     * @return array
     * @author moner khalil
     */
    public function nameVarRule(bool $isRequired = true): array
    {
        $temp_rules = ['regex:/^[a-z][a-z0-9]*$/i',new TextRule()];
        $temp_rules[] = $isRequired ? "required" : "nullable";
        return $temp_rules;
    }

    /**
     * @param bool $isRequired
     * @return array
     * @author moner khalil
     */
    public function phoneRule(bool $isRequired = true): array
    {
        $rules = ['regex:/^\d{1,3}\-[0-9][0-9]{6,15}$/i',new PhoneRule()];
        $rules[] = $isRequired ? "required" : "nullable";
        return $rules;
    }

    /**
     * @param bool $isRequired
     * @return string[]
     */
    public function numberRule(bool $isRequired = true): array{
        $rules = ["numeric"];
        $rules[] = $isRequired ? "required" : "nullable";
        return $rules;
    }

    /**
     * @param bool $isRequired
     * @return string[]
     */
    public function urlRule(bool $isRequired = true): array{
        $rules = ["url"];
        $rules[] = $isRequired ? "required" : "nullable";
        return $rules;
    }

    /**
     * @param bool $isRequired
     * @return string[]
     */
    public function emailRule(bool $isRequired = true): array{
        $rules = ["email","string"];
        $rules[] = $isRequired ? "required" : "nullable";
        return $rules;
    }

    /**
     * @param bool $isRequired
     * @return string[]
     */
    public function passwordRule(bool $isRequired = true,bool $withConfirmed = true): array{
        $rules = [
            Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->symbols(),
            "string",
        ];
        if ($withConfirmed){
            $rules[] = "confirmed";#password_confirmation
        }
        $rules[] = $isRequired ? "required" : "nullable";
        return $rules;
    }

    public static function urlIsApi(bool $withAjax = true): mixed
    {
        $request = request();
        $withAjax = $withAjax && $request->ajax();
        return $request->is('api/*') || $request->is('api') || $withAjax;
    }}