<?php

namespace App\Http\Controllers\UserControllers;

use App\Helpers\MyApp;
use App\Http\Controllers\Controller;
use App\Http\CrudFiles\Repositories\Interfaces\IFileManagerRepository;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupManagerRepository;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupUserRepository;

class DashboardController extends Controller
{
    public function __construct(
        private IFileManagerRepository $IFileManagerRepository,
        private IGroupManagerRepository $IGroupManagerRepository,
        private IGroupUserRepository $IGroupUserRepository,
    )
    {
    }

    public function mainDataDashboard(){
        $user = MyApp::Classes()->user->get();
        $myFilesCount = $this->IFileManagerRepository->queryModel()->where("user_id",$user->id)->count();
        $myGroupsPublicCount = $this->IGroupManagerRepository->queryModel()->where("user_id",$user->id)->where("type","public")->count();
        $myGroupsPrivateCount = $this->IGroupManagerRepository->queryModel()->where("user_id",$user->id)->where("type","private")->count();
        $myGroupsCount = $myGroupsPublicCount + $myGroupsPrivateCount;
        $memberInGroupsCount = $this->IGroupUserRepository->queryModel()->where("user_id",$user->id)->count();
        return $this->responseSuccess(compact("myFilesCount","myGroupsCount","myGroupsPrivateCount","myGroupsPublicCount","memberInGroupsCount"));
    }
}
