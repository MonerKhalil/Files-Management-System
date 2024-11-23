<?php

namespace App\Helpers;

use App\Helpers\ClassesProcess\CurrencyProcess;
use App\Helpers\ClassesProcess\DataBaseProcess;
use App\Helpers\ClassesProcess\LanguageProcess;
use App\Helpers\ClassesProcess\ResponseProcess;
use App\Helpers\ClassesProcess\RolesPermissions\PermissionsMap;
use App\Helpers\ClassesProcess\RolesPermissions\UserProcess;
use App\Helpers\ClassesProcess\StorageFileProcess;
use App\Helpers\ClassesProcess\StringProcess;

class MyApp
{
    const RouteDashBoard = "dashboard";
    const RouteLogin = "login";
    const VersionApi = "v1";
    /**
     * @var MyApp|null
     * @author moner khalil
     */
    private static ?self $app = null;

    public ?LanguageProcess $languageProcess = null;

    public ?CurrencyProcess $currencyProcess = null;

    public ?DataBaseProcess $dataBaseProcess = null;

    public ?StringProcess $stringProcess = null;

    public ?StorageFileProcess $fileProcess = null;

    public ?ResponseProcess $responseProcess = null;

    public ?UserProcess $user = null;

    public ?PermissionsMap $permissionsMap = null;

    public function __construct()
    {
        $this->user = new UserProcess();
        $this->stringProcess = new StringProcess();
        $this->fileProcess = new StorageFileProcess();
        $this->languageProcess = new LanguageProcess();
        $this->currencyProcess = new CurrencyProcess();
        $this->dataBaseProcess = new DataBaseProcess();
        $this->responseProcess = new ResponseProcess();
        $this->permissionsMap = new PermissionsMap();
    }

    /**
     * @return MyApp
     * @author moner khalil
     */
    public static function Classes(): MyApp
    {
        if (is_null(self::$app)){
            $mainObj = new static();
            self::$app = $mainObj;
        }
        return self::$app;
    }
}
