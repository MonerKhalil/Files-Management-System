<?php

use App\Http\Controllers\UserControllers\DashboardController;
use App\Http\Controllers\UserControllers\FileController;
use App\Http\Controllers\UserControllers\GroupController;
use App\Http\Controllers\UserControllers\UserGroupController;
use App\Models\GroupManager;
use Illuminate\Support\Facades\Route;

Route::prefix("dashboard/user")->group(function (){
    Route::get("/",[DashboardController::class,"mainDataDashboard"]);
    Route::prefix("groups")->controller(GroupController::class)->group(function (){
        Route::get("show/{type}","showMyGroups")
            ->whereIn("type",array_merge(["all"],GroupManager::TYPES));
        Route::get("create_","createGroup");
        Route::post("store","storeGroup");
        Route::prefix("group/{group_id}")->group(function (){
            Route::get("show","showGroup");
            Route::get("edit","editGroup");
            Route::put("update","updateGroup");
            Route::delete("destroy","destroyGroup");
        });
    });
    Route::prefix("files")->controller(FileController::class)->group(function (){
        Route::prefix("group/{group_id}")->group(function (){
            Route::post("upload/file","uploadFileToGroup");
            Route::prefix("file/{file_id}")->group(function (){
                Route::get("show/content","showContentFiles");
                Route::post("download","downloadFile");
                Route::post("copy","copyFile");
                Route::put("edit","editFile");
                Route::delete("remove","removeFileFromGroup");
            });
        });
        Route::post("{file_id}/move","moveFileToGroup");
    });
    Route::controller(UserGroupController::class)->group(function (){
        Route::get("search/users","searchUsers");
        Route::get("search/public/groups","searchGroupsPublic");
        Route::prefix("group/{group_id}")->group(function (){
            Route::post("add/users/by/emails","addUsersEmailToGroup");
            Route::delete("remove/users","removeUsersToGroup");
            Route::delete("leave","leaveUserFromGroup");
            Route::post("public/user/join","joinUserToGroupPublic");
            Route::post("generate/url/join/users","generateUrlJoinToGroup");
        });
        Route::post("join/user/to/group/{url_generate}","joinUserToGroupUsingUrl")->name("join.to.group.using.url");
    });
});
