<?php

namespace App\Http\Controllers\CrudControllers;

use App\Exceptions\CrudException;
use App\Http\Controllers\Controller;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupManagerRepository;
use App\Http\CrudFiles\Repositories\Interfaces\IUserRepository;
use App\Http\Requests\ProcessUserRequest;
use App\Services\PivotFileGroupUserService;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupUserRepository;
use Illuminate\Support\Facades\DB;

class GroupUserController extends Controller
{
    public function __construct(private IGroupUserRepository $IGroupUserRepository,
                                private IGroupManagerRepository $IGroupManagerRepository,
                                private IUserRepository $IUserRepository,
    )
    {
    }

    public function showUsersInGroup($group_id,PivotFileGroupUserService $userService){
        return $this->responseSuccess($userService->getUsersProcess($group_id,false));
    }

    public function showAddUsersToGroup($group_id,PivotFileGroupUserService $userService){
        return $this->responseSuccess($userService->getUsersProcess($group_id,true));
    }

    public function addUsersToGroup($group_id,ProcessUserRequest $request){
        try {
            DB::beginTransaction();
            $group = $this->IGroupManagerRepository->find($group_id);
            $users = $this->IUserRepository->queryModel()
                ->select(["id"])
                ->whereNot("id",$group->user_id)
                ->whereIn("id",$request->user_ids)
                ->pluck("id")
                ->toArray();
            $group->users()->syncWithoutDetaching($users);
            DB::commit();
            return $this->setMessageSuccess(null,"create")->responseSuccess();
        }catch (\Exception $e){
            DB::rollBack();
            throw new CrudException($e->getMessage());
        }
    }

    public function removeGroupsFromFile($group_id,ProcessUserRequest $request){
        $this->IGroupUserRepository->queryModel()
            ->where("group_id",$group_id)
            ->whereIn("user_id",$request->user_ids)
            ->forceDelete();
        return $this->setMessageSuccess(null,"delete")->responseSuccess();
    }
}
