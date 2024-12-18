<?php

namespace App\Http\Controllers\UserControllers;

use App\Helpers\MyApp;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CrudControllers\GroupManagerController;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupManagerRepository;
use App\Http\Requests\CrudRequests\GroupManagerRequest;
use App\Services\GroupService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    private $groupManageController;

    public function __construct(private IGroupManagerRepository $IGroupManagerRepository)
    {
        $this->groupManageController = new GroupManagerController($this->IGroupManagerRepository);
    }

    /**
     * @param $type
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|null
     */
    public function showMyGroups($type){
        $user = MyApp::Classes()->user->get();
        $groups = $this->IGroupManagerRepository->get(true,true,function ($query)use($user,$type){
            $query = $query->where(function ($q)use($user){
                return $q->where("group_managers.user_id",$user->id)
                    ->orWhereIn("group_managers.id",function ($q)use($user){
                        return $q->from("group_users")
                            ->select(["group_users.group_id"])
                            ->where("group_users.user_id",$user->id);
                    });
            });
            if ($type != "all"){
                $query = $query->where("group_managers.type",$type);
            }
            return $query->whereNull("group_managers.group_id");
        });
        return $this->responseSuccess(compact("groups"));
    }

    /**
     * @param $group_id
     * @param Request $request
     * @param GroupService $groupService
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|null
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function showGroup($group_id, Request $request, GroupService $groupService){
        #param order :
        #1 order_name ( created_at , name ).
        #2 order_type ( asc , desc ).
        #param filter :
        #1 types ( all , groups , files ).
        #2 isset type_group && types = groups ( private , public).
        #3 isset type_file && types = files ( files , images ).
        #4 name .
        #5 startCreatedAt && endCreatedAt .
        #ParamFilter__1
        $valueType = $request->filter['types'] ?? null;
        $valueType = in_array($valueType,["groups","files"]) ? $valueType : null;
        #ParamFilter__2
        $typeGroup = $request->filter['type_group'] ?? null;
        $typeGroup = !is_null($valueType) && in_array($typeGroup,["private" , "public"]) ? $typeGroup : null;
        #ParamFilter__3
        $typeFile = $request->filter['type_file'] ?? null;
        $typeFile = !is_null($valueType) && in_array($typeFile,["files" , "images"]) ? $typeFile : null;
        #ParamFilter__4
        $name = $request->filter['name'] ?? null;
        #ParamFilter__5
        $startDate = $request->filter['start_created_at'] ?? null;
        $endDate = $request->filter['end_created_at'] ?? null;
        #ParamsOrder...
        $order_name = in_array($request->order_name,["created_at","name"]) ? $request->order_name : "created_at";
        $order_type = in_array($request->order_type,["asc","desc"]) ? $request->order_type : "desc";
        $group = $groupService->getSingleGroup($group_id,$order_name,$order_type,$name,$startDate,$endDate,$valueType,$typeGroup,$typeFile);
        $groupService->checkAccessToGroup($group);
        return $this->responseSuccess(compact("group"));
    }

    public function createGroup(){
        return $this->groupManageController->create();
    }

    public function storeGroup(GroupManagerRequest $request){
        return $this->groupManageController->store($request);
    }

    public function editGroup($group_id){
        $group = $this->IGroupManagerRepository->find($group_id);
        $group->canAccess("update");
        return $this->groupManageController->edit($group_id,$group);
    }

    public function updateGroup(GroupManagerRequest $request,$group_id){
        $group = $this->IGroupManagerRepository->find($group_id);
        $group->canAccess("update");
        return $this->groupManageController->update($request,$group_id);
    }

    public function destroyGroup($group_id){
        $group = $this->IGroupManagerRepository->find($group_id);
        $group->canAccess("delete");
        return $this->groupManageController->delete($group_id);
    }
}
