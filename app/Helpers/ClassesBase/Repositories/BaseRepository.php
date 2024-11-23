<?php

namespace App\Helpers\ClassesBase\Repositories;

use App\Exceptions\CrudException;
use App\Helpers\ClassesBase\BaseExportData;
use App\Helpers\ClassesBase\Models\BaseTranslationModel;
use App\Helpers\ClassesStatic\AdapterData;
use App\Helpers\MyApp;
use App\Helpers\Traits\TFunctionsCrudRepository;
use App\Helpers\Traits\TMainGetData;
use Illuminate\Container\Container as App;
use App\Helpers\ClassesBase\Models\BaseModel;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

abstract class BaseRepository implements IBaseRepository
{
    use TMainGetData,TFunctionsCrudRepository;

    public string $nameTable = "";
    public string $nameTableTranslation = "";
    public ?BaseModel $model = null;

    public function __construct(App $app)
    {
        $this->makeModel($app);
        $this->nameTable = $this->model->getTable();
        $this->nameTableTranslation = ($this->model instanceof BaseTranslationModel) ? $this->nameTable."_translations" : "";
    }

    public function makeModel($app){
        try {
            $model = $app->make($this->model());
            return $this->model = $model;
        } catch (\Exception $e) {
            throw new CrudException($e->getMessage());
        }
    }

    /**
     * @description get data items without trashed
     * @param bool $isAll
     * @param bool $withFilter
     * @param callable|null $callback
     * @param bool $withRelations
     * @param array|string[] $fieldsOrders
     * @param string $typeOrder
     * @return mixed
     * @author moner khalil
     */
    public function get(bool $isAll = false, bool $withFilter = true, callable $callback = null, bool $withRelations = true, array $fieldsOrders = ["created_at"], string $typeOrder = "desc"): mixed{
        return $this->mainGetData($isAll,$withFilter,$callback,$withRelations,$fieldsOrders,$typeOrder,false);
    }

    /**
     * @description get data items trashed
     * @param bool $isAll
     * @param bool $withFilter
     * @param callable|null $callback
     * @param bool $withRelations
     * @param array|string[] $fieldsOrders
     * @param string $typeOrder
     * @return mixed
     * @author moner khalil
     */
    public function getOnlyTrashes(bool $isAll = false, bool $withFilter = true, callable $callback = null, bool $withRelations = true, array $fieldsOrders = ["created_at"], string $typeOrder = "desc"): mixed{
        return $this->mainGetData($isAll,$withFilter,$callback,$withRelations,$fieldsOrders,$typeOrder,true);
    }

    /**
     * @param $value
     * @param string $key
     * @param callable|null $callback
     * @param bool $withRelations
     * @param bool $withFail
     * @param bool $withTranslationsRelation
     * @return mixed
     * @author moner khalil
     */
    public function find($value, string $key = "id", ?callable $callback = null, bool $withRelations = true, bool $withFail = true, bool $withTranslationsRelation = false): mixed{
        $query = $this->queryModel()->withTrashed();
        $query = !is_null($callback) ? $callback($query) : $query;
        $query = $query->where($key,"=",$value);
        $query = $this->queryWithRelations($query,$withRelations);
        $query = ($this->model instanceof BaseTranslationModel) && $withTranslationsRelation ? $query->with("translations") : $query;
        return $withFail ? $query->firstOrFail() : $query->first();
        #return ($this->model instanceof BaseTranslationModel) && $withAdapterData ? AdapterData::singleDataTranslation($item) : $item;
    }

    /**
     * @param $data
     * @param bool $showMessage
     * @return mixed
     * @throws CrudException
     * @author moner khalil
     */
    public function create($data, bool $showMessage = true): mixed{
        try {
            DB::beginTransaction();
            $process = "create";
            $finalData = $this->mainEditFieldsValue($data);
            if (isset($finalData["@__translationFields__@"]) && $this->model instanceof BaseTranslationModel){
                $translationFieldsData = $finalData["@__translationFields__@"];
                unset($finalData["@__translationFields__@"]);
            }
            $item = $this->queryModel()->create($finalData);
            if (isset($translationFieldsData)){
                $this->createInTranslationTable($item->id,$translationFieldsData);
            }
            $this->logAndNotify($process,$item,$showMessage);
            DB::commit();
            return ($this->model instanceof BaseTranslationModel) ? AdapterData::singleDataTranslation($item) : $item;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception->getMessage());
        }
    }

    /**
     * @param $data
     * @param $itemId
     * @param bool $showMessage
     * @param callable|null $callback
     * @return mixed
     * @throws CrudException
     * @author moner khalil
     */
    public function update($data, $itemId, bool $showMessage = true, ?callable $callback = null): mixed{
        try {
            DB::beginTransaction();
            $process = "update";
            $finalData = $this->mainEditFieldsValue($data);
            if (isset($finalData["@__translationFields__@"]) && $this->model instanceof BaseTranslationModel){
                $translationFieldsData = $finalData["@__translationFields__@"];
                unset($finalData["@__translationFields__@"]);
            }
            $query = $this->queryModel()->where("id",$itemId);
            if (!is_null($callback)){
                $query = $callback($query);
            }
            $query->update($finalData);
            if (isset($translationFieldsData)){
                $this->createOrUpdateInTranslationTable($itemId,$translationFieldsData);
            }
            $item = $query->first();
            if (is_null($item)){
                throw new \Exception("the item is not found -_-");
            }
            $this->logAndNotify($process,$item,$showMessage);
            DB::commit();
            return ($this->model instanceof BaseTranslationModel) ? AdapterData::singleDataTranslation($item) : $item;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception->getMessage());
        }
    }

    public function delete($value, string $key = "id", bool $showMessage = true,?callable $callback = null): bool{
        try {
            DB::beginTransaction();
            $process = "delete";
            $query = $this->queryModel()->where($key,$value);
            if (!is_null($callback)){
                $query = $callback($query);
            }
            $query->delete();
            $this->logAndNotify($process,collect([$key => $value]),$showMessage);
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception->getMessage());
        }
    }

    public function multiDelete(array $values, string $key = "id", bool $showMessage = true, ?callable $callback = null): bool{
        try {
            DB::beginTransaction();
            $process = "multi_delete";
            $query = $this->queryModel()->whereIn($key,$values);
            if (!is_null($callback)){
                $query = $callback($query);
            }
            $query->delete();
            $this->logAndNotify($process,collect([$key => $values]),$showMessage);
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception->getMessage());
        }
    }

    public function forceDelete($value, string $key = "id", bool $showMessage = true,?callable $callback = null): bool{
        try {
            DB::beginTransaction();
            $process = "force_delete";
            $query = $this->queryModel()->withTrashed()->where($key,$value);
            if (!is_null($callback)){
                $query = $callback($query);
            }
            $files = $this->viewFields()->fieldsFiles();
            if (sizeof($files) > 0){
                $item = $query->first();
                foreach ($files as $file){
                    MyApp::Classes()->fileProcess->deleteFile($item->{$file});
                }
                $item->forceDelete();
                $this->logAndNotify($process,$item,$showMessage);
            }else{
                $query->forceDelete();
                $this->logAndNotify($process,collect([$key => $value]),$showMessage);
            }
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception->getMessage());
        }
    }

    public function multiForceDelete(array $values, string $key = "id", bool $showMessage = true,?callable $callback = null): bool{
        try {
            DB::beginTransaction();
            $process = "multi_force_delete";
            $query = $this->queryModel()->withTrashed()->whereIn($key,$values);
            if (!is_null($callback)){
                $query = $callback($query);
            }
            $files = $this->viewFields()->fieldsFiles();
            if (sizeof($files) > 0){
                $items = $query->select($files)->get();
                foreach ($files as $file){
                    foreach ($items as $item){
                        MyApp::Classes()->fileProcess->deleteFile($item->{$file});
                    }
                }
            }
            $query->forceDelete();
            $this->logAndNotify($process,collect([$key => $values]),$showMessage);
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception->getMessage());
        }
    }

    public function restore($value, string $key = "id", bool $showMessage = true, ?callable $callback = null): bool{
        try {
            DB::beginTransaction();
            $process = "restore";
            $query = $this->queryModel()->onlyTrashed()->where($key,$value);
            if (!is_null($callback)){
                $query = $callback($query);
            }
            $query->restore();
            $this->logAndNotify($process,collect([$key => $value]),$showMessage);
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception->getMessage());
        }
    }

    public function multiRestore(array $values, string $key = "id", bool $showMessage = true, ?callable $callback = null): bool{
        try {
            DB::beginTransaction();
            $process = "multi_restore";
            $query = $this->queryModel()->onlyTrashed()->where($key,$values);
            if (!is_null($callback)){
                $query = $callback($query);
            }
            $query->restore();
            $this->logAndNotify($process,collect([$key => $values]),$showMessage);
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception->getMessage());
        }
    }

    public function restoreAll(bool $showMessage = true, ?callable $callback = null): bool{
        try {
            DB::beginTransaction();
            $process = "all_restore";
            $query = $this->queryModel()->onlyTrashed();
            if (!is_null($callback)){
                $query = $callback($query);
            }
            $query->restore();
            $this->logAndNotify($process,"all",$showMessage);
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception->getMessage());
        }
    }

    public function clearTrashes(bool $showMessage = true ,?callable $callback = null){
        try {
            DB::beginTransaction();
            $process = "clear_trashed";
            $query = $this->queryModel()->onlyTrashed();
            if (!is_null($callback)){
                $query = $callback($query);
            }
            $files = $this->viewFields()->fieldsFiles();
            if (sizeof($files) > 0){
                $items = $query->select($files)->get();
                foreach ($files as $file){
                    foreach ($items as $item){
                        MyApp::Classes()->fileProcess->deleteFile($item->{$file});
                    }
                }
            }
            $query->forceDelete();
            $this->logAndNotify($process,"all",$showMessage);
            DB::commit();
            return true;
        }catch (\Exception $exception){
            DB::rollBack();
            throw new CrudException($exception->getMessage());
        }
    }

    public function exportXLSX(bool $isEmpty = false,?array $values = null, string $key = "id",?callable $callback = null): mixed{
        try {
            if ($isEmpty){
                $dataTable = new Collection([]);
                $headTable = Arr::except($this->viewFields()->getFieldsCreate(),$this->viewFields()->fieldsFiles());
            }else{
                $mainData = $this->getMainDataInExport($values,$key,$callback);
                $dataTable = $mainData['dataTable'];
                $headTable = $mainData['headTable'];
            }
            $this->logAndNotify("export_xlsx",null,false);
            return Excel::download(new BaseExportData($headTable,$dataTable,null,[],true),$this->nameTable . '.xlsx');
        }catch (\Exception $exception){
            throw new CrudException($exception->getMessage());
        }
    }

    public function exportPDF(?array $values = null, string $key = "id",?callable $callback = null): mixed{
        try {
            $data = $this->getMainDataInExport($values,$key,$callback);

            $pdf = PDF::loadView('ExportCrud.export.pdf', [
                "data" => $data,
            ]);

            $this->logAndNotify("export_pdf",null,false);

            return $pdf->download($this->nameTable . '.pdf');
        }catch (\Exception $exception){
            throw new CrudException($exception->getMessage());
        }
    }
}
