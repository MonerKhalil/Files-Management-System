<?php

namespace App\Http\Controllers\UserControllers;

use App\Exceptions\MainException;
use App\Helpers\ClassesProcess\RolesPermissions\Roles;
use App\Helpers\MyApp;
use App\Http\Controllers\Controller;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupManagerRepository;
use App\Http\CrudFiles\Repositories\Interfaces\IUserRepository;
use App\Http\Requests\ProcessUserRequest;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{
    public function __construct(private IUserRepository $IUserRepository,
                                private IGroupManagerRepository $IGroupManagerRepository)
    {
    }

    public function searchGroupsPublic(Request $request){
        $name = $request->filter['name'] ?? null;
        $groups = is_null($name) ? collect([]) : $this->IGroupManagerRepository->get(true,false,function ($q)use($name){
            return $q->where("name","LIKE","%{$name}%");
        });
        return $this->responseSuccess(compact("groups"));
    }

    public function searchUsers(Request $request){
        $name = $request->filter['name'] ?? null;
        $email = $request->filter['email'] ?? null;
        $users = is_null($name) && is_null($email) ? collect([]) :
        $this->IUserRepository->get(true,false,function ($q)use ($name,$email){
            $q = !is_null($name) ? $q->where("name","LIKE","%{$name}%") : $q;
            $q = !is_null($email) ? $q->where("email","LIKE","%{$email}%") : $q;
            $superAdmin = MyApp::Classes()->cacheProcess->getAllRoles(Roles::ADMIN);
            return $q->whereNot("role_id",$superAdmin?->id);
        });
        return $this->responseSuccess(compact("users"));
    }

    public function addUsersEmailToGroup($group_id,ProcessUserRequest $request){
        $group = $this->IGroupManagerRepository->find($group_id);
        $group->canAccessProcessUsers(true);
        $group->users()->syncWithoutDetaching($request->user_ids);
        return $this->responseSuccess();
    }

    public function removeUsersToGroup($group_id,ProcessUserRequest $request){
        $group = $this->IGroupManagerRepository->find($group_id);
        $group->canAccessProcessUsers(true);
        $group->users()->detach($request->user_ids);
        return $this->responseSuccess();
    }

    public function leaveUserFromGroup($group_id){
        $group = $this->IGroupManagerRepository->find($group_id);
        $user = MyApp::Classes()->user->get();
        $group->users()->detach([$user->id]);
        return $this->responseSuccess();
    }

    public function joinUserToGroupPublic($group_id){
        $group = $this->IGroupManagerRepository->find($group_id);
        if ($group->type != "public"){
            throw new MainException(__("errors.group_is_not_public"));
        }
        $user = MyApp::Classes()->user->get();
        $group->users()->syncWithoutDetaching([$user->id]);
        return $this->responseSuccess(compact("group"));
    }

    public function generateUrlJoinToGroup($group_id){
        $group = $this->IGroupManagerRepository->find($group_id);
        $group->canAccessProcessUsers(true);
        $code = uniqid();
        $group->update([
            "url_generate" => $code,
        ]);
        $group->url_generate = $code;
        $apiJoinToGroup = route("join.to.group.using.url",[
            "url_generate" => $code,
        ]);
        return $this->responseSuccess(compact("apiJoinToGroup","group"));
    }

    public function joinUserToGroupUsingUrl($url_generate){
        $group = $this->IGroupManagerRepository->find($url_generate,"url_generate");
        $user = MyApp::Classes()->user->get();
        $group->users()->syncWithoutDetaching([$user->id]);
        return $this->responseSuccess(compact("group"));
    }

}
