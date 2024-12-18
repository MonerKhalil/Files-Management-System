<?php

namespace App\Services;

use App\Exceptions\CrudException;
use App\Http\CrudFiles\Repositories\Interfaces\IRoleRepository;
use App\Models\Permission;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RoleService
{
    public function __construct(private IRoleRepository $IRoleRepository){
    }

    public function createOrUpdate($data,$permissions,$id = null){
        try {
            DB::beginTransaction();
            $dataRole = Arr::except($data,["permissions"]);
            if (is_null($id)){
                $item = $this->IRoleRepository->create($dataRole);
            }else{
                $item = $this->IRoleRepository->update($dataRole,$id);
            }
            $permissions = Permission::query()
                ->select(["id"])
                ->whereIn("id",$permissions)
                ->pluck("id")
                ->toArray();
            $item->syncPermissions($permissions);
            DB::commit();
            return compact("item","permissions");
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception->getMessage());
        }
    }

    public function getPermissions(){
        $tables = $this->getAllNameTables();
        $permissions = Permission::query()->get(["id","name"]);
        return $this->finalDataPermissions($permissions,$tables);
    }

    private function getAllNameTables()
    {
        return collect(DB::query()->from("information_schema.TABLES")
            ->select(["table_name"])
            ->where("table_schema",env("DB_DATABASE"))
            ->whereNot("table_name","LIKE","%_translations%")
            ->whereNotIn("table_name",[
                'oauth_access_tokens', 'oauth_auth_codes', 'oauth_clients', 'oauth_personal_access_clients',
                'telescope_entries', 'telescope_entries_tags', 'telescope_monitoring',
                'oauth_refresh_tokens', 'audits', 'failed_jobs', 'migrations', 'personal_access_tokens',
                'password_resets', 'notifications','websockets_statistics_entries',
                'permission_role','permission_user','permissions','role_user'
            ])
            ->get())->pluck("table_name")->toArray();
    }

    private function finalDataPermissions($permissions,$tables){
        $Temp = [];
        foreach ($permissions as $permission) {
            $tempPer = explode("_", $permission->name);
            unset($tempPer[0]);
            $table = implode("_", $tempPer);
            if (in_array($table,$tables)){
                $Temp[$table][] = $permission;
            }else{
                $Temp["other"][] = $permission;
            }
        }
        return $Temp;
    }
}
