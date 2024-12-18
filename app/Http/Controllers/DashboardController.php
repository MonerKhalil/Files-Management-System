<?php

namespace App\Http\Controllers;

use App\Helpers\ClassesProcess\RolesPermissions\Roles;
use App\Helpers\MyApp;
use App\Http\CrudFiles\Repositories\Interfaces\IUserRepository;
use App\Http\CrudFiles\Repositories\Interfaces\IFileManagerRepository;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupManagerRepository;

class DashboardController extends Controller
{
    public function __construct(
        private IUserRepository $IUserRepository,
        private IFileManagerRepository $IFileManagerRepository,
        private IGroupManagerRepository $IGroupManagerRepository,
    )
    {
        $role = Roles::ADMIN;
        $this->middleware("roles:$role")->only(["mainDataDashboard"]);
    }

    public function mainDataDashboard(){
        $users = $this->IUserRepository->get(true,false,null,false);
        $rolesUsersCount = $this->countRoles($users);
        $rolesCount = count($rolesUsersCount);
        $usersCount = count($users);
        $filesCount = $this->IFileManagerRepository->queryModel()->count();
        $groupsPublicCount = $this->IGroupManagerRepository->queryModel()->where("type","public")->count();
        $groupsPrivateCount = $this->IGroupManagerRepository->queryModel()->where("type","private")->count();
        $groupsCount = $groupsPrivateCount + $groupsPublicCount;
        return $this->responseSuccess(compact("rolesUsersCount","rolesCount","usersCount","filesCount","groupsPrivateCount","groupsPublicCount","groupsCount"));
    }

    private function countRoles($users){
        $roles = MyApp::Classes()->cacheProcess->getAllRoles();
        $users = collect($users??[]);
        $rolesCount = [];
        foreach ($roles as $role){
            $rolesCount[$role->name] = $users->where("role_id",$role->id)->count();
        }
        return $rolesCount;
    }
}
