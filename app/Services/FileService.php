<?php

namespace App\Services;

use App\Exceptions\MainException;
use App\Helpers\MyApp;
use App\Http\CrudFiles\Repositories\Interfaces\IFileManagerRepository;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupFileRepository;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupUserRepository;
use App\Models\FileManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function __construct(private IGroupUserRepository $IGroupUserRepository,
                                private IGroupFileRepository $IGroupFileRepository,
                                private IFileManagerRepository $IFileManagerRepository,
                                private GroupService $groupService
    )
    {
    }

    public function moveFile($fromGroup,$toGroup,$file){
        try {
            DB::beginTransaction();
            $this->checkUserExistsInGroupsFile($toGroup,$file);
            $this->IGroupFileRepository->forceDelete($fromGroup->id,"group_id",false,function ($q)use($file){
                return $q->where("file_id",$file->id);
            });
            $this->IGroupFileRepository->create([
                "group_id" => $toGroup->id,
                "file_id" => $file->id,
            ],false);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    public function copyFile($group,$file){
        try {
            DB::beginTransaction();
            $this->checkUserExistsInGroupsFile($group,$file);
            $copyFile = $this->createCopyFile($file);
            $this->IGroupFileRepository->create([
                "group_id" => $group->id,
                "file_id" => $file->id,
            ]);
            DB::commit();
            return $copyFile;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new MainException($exception->getMessage());
        }
    }

    private function createCopyFile($file){
        $mainPath = Storage::disk(FileManager::DISK)->get($file->file);
        if (!file_exists($mainPath)){
            throw new MainException(__("errors.file_not_found"));
        }
        $fileNew = new UploadedFile($mainPath,$file->name_default);
        return $this->IFileManagerRepository->create([
            "name" => $file->name,
            "file" => $fileNew,
        ],false);
    }

    private function checkUserExistsInGroupsFile($groupMain ,$file){
        $user = MyApp::Classes()->user->get();
        $groupsFile = collect($file->groups_pivot??[])->pluck("group_id")->toArray();
        $groupsUser = $this->IGroupUserRepository->get(true,false,function ($q)use ($user){
            return $q->select(["group_id"])->where("user_id",$user->id);
        });
        if ($groupMain->canAccessProcessFiles(false)){
            return;
        }
        foreach ($groupsUser as $group){
            if (in_array($group->group_id,$groupsFile) && $groupMain->id == $group->id){
                return;
            }
        }
        throw new MainException(__("errors.file_dont_exists_in_any_groups"));
    }

    public function getPathFileInProcessShowContentAndDownload($group_id,$file_id){
        $group = $this->IGroupManagerRepository->find($group_id,"id",function ($q){
            return $q->with(["users"]);
        });
        $this->groupService->checkAccessToGroup($group);
        $file = $this->IFileManagerRepository->find($file_id,"id",function ($q){
            return $q->with(["groups_pivot"]);
        });
        $this->checkUserExistsInGroupsFile($group,$file);
        return $file->path;
    }
}
