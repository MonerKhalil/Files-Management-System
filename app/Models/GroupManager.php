<?php

namespace App\Models;

use App\Helpers\ClassesBase\Models\BaseModel;
use App\Helpers\MyApp;
use Illuminate\Auth\Access\AuthorizationException;

class GroupManager extends BaseModel
{
    const TYPES = ["private","public"];

    #-Relations-Functions-------

    public function group(){
        return $this->belongsTo(__CLASS__,"group_id","id");
    }

    public function groups(){
        return $this->hasMany(__CLASS__,"group_id","id");
    }

    public function user(){
        return $this->belongsTo(User::class,"user_id","id");
    }

    public function files_pivot(){
        return $this->hasMany(GroupFile::class,"group_id","id");
    }

    public function files(){
        return $this->belongsToMany(FileManager::class,"group_files","group_id","file_id")
            ->withTimestamps()->detach();
    }

    public function users_pivot(){
        return $this->hasMany(GroupUser::class,"group_id","id");
    }

    public function users(){
        return $this->belongsToMany(User::class,"group_users","group_id","user_id")
            ->withTimestamps();
    }

    public function canAccess($process,$withException = true){
        return $this->mainCheckFunction("{$process}_group_managers",$withException);
    }

    public function canAccessProcessFiles($withException = true){
        return $this->mainCheckFunction("process_files_in_group_managers",$withException);
    }

    public function canAccessProcessUsers($withException = true){
        return $this->mainCheckFunction("process_users_in_group_managers",$withException);
    }

    private function mainCheckFunction(string|array $permissions,bool $withException = true){
        $user = MyApp::Classes()->user->get();
        if (is_array($permissions)){
            $permissions[] = "all_group_managers";
        }else{
            $permissions = ["all_group_managers",$permissions];
        }
        $mainCheck = ($user->id == $this->user_id || MyApp::Classes()->user->checkPermissionExists($permissions));
        if ($withException){
            if (!$mainCheck){
                throw new AuthorizationException(__("errors.no_permission"));
            }
        }
        return $mainCheck;
    }
}
