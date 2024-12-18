<?php

namespace App\Http\CrudFiles\Repositories\Eloquent;

use App\Exceptions\CrudException;
use App\Http\CrudFiles\Repositories\Interfaces\IEmailConfigurationRepository;
use App\Http\CrudFiles\ViewFields\EmailConfigurationViewFields;
use App\Http\CrudFiles\Actions\EmailConfigurationAction;
use App\Models\EmailConfiguration;
use App\Helpers\ClassesBase\Repositories\BaseRepository;
use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;
use Illuminate\Support\Facades\DB;

class EmailConfigurationRepository extends BaseRepository implements IEmailConfigurationRepository
{
    public function model(){
        return EmailConfiguration::class;
    }

    public function queryModel(){
        return EmailConfiguration::query();
    }

    public function viewFields():BaseViewFields{
        return new EmailConfigurationViewFields($this);
    }

    public function actions():CrudActions{
        return new EmailConfigurationAction($this);
    }

    public function create($data, bool $showMessage = true): mixed
    {
        try {
            DB::beginTransaction();
            if (isset($data['default']) && $data['default']){
                $this->queryModel()->update([
                    "default" => 0,
                ]);
            }
            $response = parent::create($data, $showMessage); // TODO: Change the autogenerated stub
            DB::commit();
            return $response;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception);
        }
    }

    public function update($data, $itemId, bool $showMessage = true, ?callable $callback = null): mixed
    {
        try {
            DB::beginTransaction();
            if (isset($data['default']) && $data['default']){
                $this->queryModel()->update([
                    "default" => 0,
                ]);
            }else{
                $current = $this->queryModel()->where("id",$itemId)->first();
                if ($current->default ?? true){
                    throw new \Exception(__("errors.cannot_edit_default"));//
                }
            }
            $response = parent::update($data, $itemId, $showMessage, $callback);
            DB::commit();
            return $response;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception);
        }
    }
}