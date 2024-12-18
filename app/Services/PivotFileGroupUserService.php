<?php

namespace App\Services;

use App\Http\CrudFiles\Repositories\Interfaces\IFileManagerRepository;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupManagerRepository;
use App\Http\CrudFiles\Repositories\Interfaces\IUserRepository;

class PivotFileGroupUserService
{
    public function __construct(private IGroupManagerRepository $IGroupManagerRepository,
                                private IFileManagerRepository $IFileManagerRepository,
                                private IUserRepository $IUserRepository
    ){

    }

    public function getGroupsProcess($file_id,bool $onlyGroupsWithOutFile){
        $file = $this->IFileManagerRepository->find($file_id,"id",function ($q){
            return $q->with(["groups_pivot" => function($q_groups_pivot){
                return $q_groups_pivot->select(["group_id"]);
            }]);
        });
        $groups = collect($file->groups_pivot ?? [])->pluck("group_id")->toArray();
        $groups = $this->IGroupManagerRepository->get(true,true,function ($q)use($groups,$onlyGroupsWithOutFile){
            return $onlyGroupsWithOutFile ? $q->whereNotIn("id",$groups) : $q->whereIn("id",$groups);
        });
        return compact("file","groups");
    }

    public function getFilesProcess($group_id,bool $onlyFilesWithOutGroup){
        $group = $this->IGroupManagerRepository->find($group_id,"id",function ($q){
            return $q->with(["files_pivot" => function($q_files_pivot){
                return $q_files_pivot->select(["file_id"]);
            }]);
        });
        $files = collect($group->files_pivot ?? [])->pluck("file_id")->toArray();
        $files = $this->IGroupManagerRepository->get(true,true,function ($q)use($files,$onlyFilesWithOutGroup){
            return $onlyFilesWithOutGroup ? $q->whereNotIn("id",$files) : $q->whereIn("id",$files);
        });
        return compact("group","files");
    }

    public function getUsersProcess($group_id,bool $onlyUsersWithOutGroup){
        $group = $this->IGroupManagerRepository->find($group_id,"id",function ($q){
            return $q->with(["users_pivot" => function($q_users_pivot){
                return $q_users_pivot->select(["user_id"]);
            }]);
        });
        $users = collect($group->users_pivot ?? [])->pluck("user_id")->toArray();
        $users = $this->IUserRepository->get(true,true,function ($q)use($users,$onlyUsersWithOutGroup){
            return $onlyUsersWithOutGroup ? $q->whereNotIn("id",$users) : $q->whereIn("id",$users);
        });
        return compact("group","users");
    }
}
