<?php

namespace App\Helpers\ClassesProcess;

use App\Models\EmailConfiguration;
use App\Models\GeneralSetting;
use App\Models\Role;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Cache;
use App\Http\CrudFiles\Repositories\Eloquent\EmailConfigurationRepository;
use App\Http\CrudFiles\Repositories\Eloquent\GeneralSettingRepository;
use App\Http\CrudFiles\Repositories\Eloquent\RoleRepository;
use App\Http\CrudFiles\Repositories\Eloquent\WebsiteSettingRepository;

class CacheMainDataProcess
{
    public function getWebsiteSettings($key = null){
        $settings =  Cache::remember(WebsiteSetting::NAME_CACHE,null,function (){
            return app(WebsiteSettingRepository::class)->get(true,false,null,false);
        });
        return is_null($key) ? $settings : $settings->where("key",$key)->first();
    }

    public function getGeneralSettings($key = null){
        $settings =  Cache::remember(GeneralSetting::NAME_CACHE,null,function (){
            return app(GeneralSettingRepository::class)->get(true,false,null,false);
        });
        return is_null($key) ? $settings : $settings->where("key",$key)->first();
    }

    public function getAllRoles($role = null,$key = "name"){
        $roles = Cache::remember(Role::ROLE_NAME_CACHE,null,function (){
            return app(RoleRepository::class)->get(true,false,null,false);
        });
        return is_null($role) ? $roles : $roles->where($key,$role)->first();
    }

    public function getDefaultConfigMail(){
        return Cache::remember(EmailConfiguration::DEF_NAME_CACHE,null,function (){
            return app(EmailConfigurationRepository::class)->find(true,"default");
        });
    }
}
