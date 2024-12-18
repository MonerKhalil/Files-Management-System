<?php

namespace App\Models;

use App\Helpers\ClassesBase\Models\BaseModel;
use App\Helpers\MyApp;
use Illuminate\Auth\Access\AuthorizationException;

class FileManager extends BaseModel
{
    const DISK = "folder_private_uploads";

    public static function getExtinction(){
        return MyApp::Classes()->fileProcess->getExImages(true) + MyApp::Classes()->fileProcess->getExFiles(true);
    }
    #-Relations-Functions-------

    public function user(){
        return $this->belongsTo(User::class,"user_id","id");
    }

    public function groups_pivot(){
        return $this->hasMany(GroupFile::class,"file_id","id");
    }

    public function groups(){
        return $this->belongsToMany(GroupManager::class,"group_files","file_id","group_id")
            ->withTimestamps();
    }

    public function canAccess($process){
        $user = MyApp::Classes()->user->get();
        if (!($user->id == $this->user_id || MyApp::Classes()->user->checkPermissionExists(["all_file_managers","{$process}_file_managers"]))){
            throw new AuthorizationException(__("errors.no_permission"));
        }
    }
}
