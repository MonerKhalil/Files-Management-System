<?php

namespace App\Services;

use App\Helpers\MyApp;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use App\Http\CrudFiles\Repositories\Interfaces\IGroupManagerRepository;

class GroupService
{
    public function __construct(private IGroupManagerRepository $IGroupManagerRepository)
    {
    }

    public function checkAccessToGroup($group){
        $user = MyApp::Classes()->user->get();
        $users = collect($group->users ?? [])->pluck("id")->toArray();
        if (!( in_array($user->id,$users) || $group->user_id == $user->id || MyApp::Classes()->user->checkPermissionExists(["all_group_managers","read_index_group_managers"]) )){
            throw new AuthorizationException(__("errors.no_permission"));
        }
    }

    public function getSingleGroup($group_id,$order_name,$order_type,$name,$startDate,$endDate,$valueType,$typeGroup,$typeFile){
        return $this->IGroupManagerRepository->find($group_id,"id",function ($q)use($order_name,$order_type,$name,$startDate,$endDate,$valueType,$typeGroup,$typeFile){
            $q = $q->with(["users"]);
            return match ($valueType){
                null => $q->with([
                    "groups" => $this->getClosureQuery($name,$typeGroup,$startDate,$endDate,$order_name,$order_type),
                    "files" => $this->getClosureQuery($name,$typeFile,$startDate,$endDate,$order_name,$order_type),
                ]),
                "groups" => $q->with(["groups" =>$this->getClosureQuery($name,$typeGroup,$startDate,$endDate,$order_name,$order_type),]),
                "files" => $q->with(["files" => $this->getClosureQuery($name,$typeFile,$startDate,$endDate,$order_name,$order_type),]),
                default => $q
            };
        });
    }

    private function getClosureQuery($name,$type,$startDate,$endDate,$order_name,$order_type){
        return function ($query)use($name,$type,$startDate,$endDate,$order_name,$order_type){
            if (!is_null($name)){
                $query = $query->where("name","LIKE","%{$name}%");
            }
            if (!is_null($type)){
                $query = $query->where("type",$type);
            }
            if (!is_null($startDate) && !is_null($endDate)){
                $query = $query->whereBetween(DB::raw('DATE(created_at)'),[MyApp::Classes()->stringProcess->dateFormat($startDate),MyApp::Classes()->stringProcess->dateFormat($endDate)]);
            }
            return $query->orderBy($order_name,$order_type);
        };
    }
}
