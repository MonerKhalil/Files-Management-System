<?php

namespace App\Http\Controllers\UserControllers;

use App\Exceptions\MainException;
use App\Helpers\MyApp;
use App\Http\Controllers\Controller;
use App\Http\CrudFiles\Repositories\Interfaces\IFileManagerRepository;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupManagerRepository;
use App\Http\Requests\FileUserRequest;
use App\Http\Requests\MoveFileRequest;
use App\Models\FileManager;
use App\Services\FileService;
use App\Services\GroupService;
use Illuminate\Support\Facades\DB;

class FileController extends Controller
{
    public function __construct(
        private IFileManagerRepository $IFileManagerRepository,
        private IGroupManagerRepository $IGroupManagerRepository
    )
    {
    }

    public function uploadFileToGroup($group_id,FileUserRequest $request,GroupService $groupService){
        try {
            DB::beginTransaction();
            $group = $this->IGroupManagerRepository->find($group_id,"id",function ($q){
                return $q->with(["users"]);
            });
            $groupService->checkAccessToGroup($group);
            $file = $this->IFileManagerRepository->create($request->validated());
            $file->groups()->syncWithoutDetaching([$group_id]);
            DB::commit();
            return $this->responseSuccess(compact("group","file"));
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    public function copyFile($group_id,$file_id,GroupService $groupService,FileService $fileService){
        $group = $this->IGroupManagerRepository->find($group_id,"id",function ($q){
            return $q->with(["users"]);
        });
        $groupService->checkAccessToGroup($group);
        $file = $this->IFileManagerRepository->find($file_id,"id",function ($q){
            return $q->with(["groups_pivot"]);
        });
        $fileCopy = $fileService->copyFile($group,$file);
        return $this->responseSuccess(compact("group","file","fileCopy"));
    }

    public function moveFileToGroup($file_id,MoveFileRequest $request,GroupService $groupService,FileService $fileService){
        $fromGroup = $this->IGroupManagerRepository->find($request->from_group_id,"id",function ($q){
            return $q->with(["users"]);
        });
        $groupService->checkAccessToGroup($fromGroup);
        $toGroup = $this->IGroupManagerRepository->find($request->to_group_id,"id",function ($q){
            return $q->with(["users"]);
        });
        $groupService->checkAccessToGroup($toGroup);
        $file = $this->IFileManagerRepository->find($file_id,"id",function ($q){
            return $q->with(["groups_pivot"]);
        });
        $fileService->moveFile($fromGroup,$toGroup,$file);
        return $this->setMessageSuccess(null,"default")->responseSuccess();
    }

    public function editFile($group_id,$file_id,FileUserRequest $request){
        $group = $this->IGroupManagerRepository->find($group_id);
        $group->canAccessProcessFiles(true);
        $file = $this->IFileManagerRepository->update($request->validated(),$file_id);
        return $this->responseSuccess(compact("file"));
    }

    public function removeFileFromGroup($group_id,$file_id){
        $group = $this->IGroupManagerRepository->find($group_id);
        $group->canAccessProcessFiles(true);
        $this->IFileManagerRepository->forceDelete($file_id);
        return $this->responseSuccess();
    }

    public function showContentFiles($group_id,$file_id,FileService $fileService){
        $path = $fileService->getPathFileInProcessShowContentAndDownload($group_id,$file_id);
        return MyApp::Classes()->fileProcess->responseFile($path,FileManager::DISK);
    }

    public function downloadFile($group_id,$file_id,FileService $fileService){
        $path = $fileService->getPathFileInProcessShowContentAndDownload($group_id,$file_id);
        return MyApp::Classes()->fileProcess->downloadFile($path,FileManager::DISK);
    }
}
