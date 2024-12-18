<?php

namespace App\Helpers\ClassesBase\Repositories;

use App\Helpers\ClassesBase\BaseViewFields;
use App\Helpers\ClassesBase\Routes\CrudActions;

interface IBaseRepository
{
    public function model();
    public function queryModel();
    public function viewFields():BaseViewFields;
    public function actions():CrudActions;
    #get_data
    public function get(bool $isAll = false, bool $withFilter = true, callable $callback = null, bool $withRelations = true, array $fieldsOrders = ["created_at"], string $typeOrder = "desc"): mixed;
    public function getOnlyTrashes(bool $isAll = false, bool $withFilter = true, callable $callback = null, bool $withRelations = true, array $fieldsOrders = ["created_at"], string $typeOrder = "desc"): mixed;
    public function find($value, string $key = "id", ?callable $callback = null, bool $withRelations = true, bool $withFail = true, bool $withTranslationsRelation = false): mixed;
    #proccess_data
    public function create($data, bool $showMessage = true,array $fieldsFile = []): mixed;
    public function update($data, $itemId, bool $showMessage = true,?callable $callback = null,array $fieldsFile = []): mixed;
    #export_data
    public function exportXLSX(bool $isEmpty = false,?array $values = null, string $key = "id",?callable $callback = null): mixed;
    public function exportPDF(?array $values = null, string $key = "id",?callable $callback = null): mixed;
    #destroy_data
    public function delete($value, string $key = "id", bool $showMessage = true,?callable $callback = null): bool;
    public function multiDelete(array $values, string $key = "id", bool $showMessage = true, ?callable $callback = null): bool;
    public function forceDelete($value, string $key = "id", bool $showMessage = true,?callable $callback = null): bool;
    public function multiForceDelete(array $values, string $key = "id", bool $showMessage = true,?callable $callback = null): bool;
    #restore_data
    public function restore($value, string $key = "id", bool $showMessage = true, ?callable $callback = null): bool;
    public function multiRestore(array $values, string $key = "id", bool $showMessage = true, ?callable $callback = null): bool;
    public function restoreAll(bool $showMessage = true, ?callable $callback = null): bool;
    public function clearTrashes(bool $showMessage = true ,?callable $callback = null);

    #########################################################################################################################
    #    public function copyRowShow(int $idOldModel, ?string $nameFieldData = null): mixed;
    #    public function createMany($manyData,bool $isRequest = true ,bool $showMessage = true,array $valuesIsFiles = []):bool;
    #    public function editTable($request,?bool $checkKeyInObjTranslation = null,$keyInObjTranslation = null,array $valuesIsFiles = [],array $canRepetition = []):bool;
    #########################################################################################################################

}
