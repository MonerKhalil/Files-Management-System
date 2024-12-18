<?php

namespace App\Http\Controllers\CrudControllers;

use App\Exceptions\CrudException;
use App\Http\Controllers\Controller;
use App\Http\CrudFiles\Repositories\Interfaces\IFileManagerRepository;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupManagerRepository;
use App\Http\Requests\ProcessFileRequest;
use App\Http\Requests\ProcessGroupRequest;
use App\Services\PivotFileGroupUserService;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupFileRepository;
use Illuminate\Support\Facades\DB;

class GroupFileController extends Controller
{
    public function __construct(private IGroupFileRepository $IGroupFileRepository,
                                private IGroupManagerRepository $IGroupManagerRepository,
                                private IFileManagerRepository $IFileManagerRepository
    ){

    }

    ############################################# FILES #############################################

    public function showGroupsFile($file_id,PivotFileGroupUserService $fileService){
        return $this->responseSuccess($fileService->getGroupsProcess($file_id,false));
    }

    public function showAddGroupsToFile($file_id,PivotFileGroupUserService $fileService){
        return $this->responseSuccess($fileService->getGroupsProcess($file_id,true));
    }

    public function addGroupsToFile($file_id,ProcessGroupRequest $request){
        try {
            DB::beginTransaction();
            $groups = $this->IGroupManagerRepository->queryModel()->select(["id"])->whereIn("id",$request->group_ids)->pluck("id")->toArray();
            $file = $this->IFileManagerRepository->find($file_id);
            $file->groups()->syncWithoutDetaching($groups);
            DB::commit();
            return $this->setMessageSuccess(null,"create")->responseSuccess();
        }catch (\Exception $e){
            DB::rollBack();
            throw new CrudException($e->getMessage());
        }
    }

    public function removeGroupsFromFile($file_id,ProcessGroupRequest $request){
        $this->IGroupFileRepository->queryModel()
            ->where("file_id",$file_id)
            ->whereIn("group_id",$request->group_ids)
            ->forceDelete();
        return $this->setMessageSuccess(null,"delete")->responseSuccess();
    }

    ############################################# FILES #############################################

    ############################################# GROUPS #############################################

    public function showFilesGroup($group_id,PivotFileGroupUserService $groupService){
        return $this->responseSuccess($groupService->getFilesProcess($group_id,false));
    }

    public function showAddFilesToGroup($group_id,PivotFileGroupUserService $groupService){
        return $this->responseSuccess($groupService->getFilesProcess($group_id,true));
    }

    public function addFilesToGroup($group_id,ProcessFileRequest $request){
        try {
            DB::beginTransaction();
            $files = $this->IFileManagerRepository->queryModel()->select(["id"])->whereIn("id",$request->file_ids)->pluck("id")->toArray();
            $group = $this->IFileManagerRepository->find($group_id);
            $group->files()->syncWithoutDetaching($files);
            DB::commit();
            return $this->setMessageSuccess(null,"create")->responseSuccess();
        }catch (\Exception $e){
            DB::rollBack();
            throw new CrudException($e->getMessage());
        }
    }

    public function removeFilesFromGroup($group_id,ProcessFileRequest $request){
        $this->IGroupFileRepository->queryModel()
            ->where("group_id",$group_id)
            ->whereIn("file_id",$request->file_ids)
            ->forceDelete();
        return $this->setMessageSuccess(null,"delete")->responseSuccess();
    }

    ############################################# GROUPS #############################################

}
