<?php

namespace App\Http\Controllers\CrudControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CrudRequests\EmailConfigurationRequest;
use App\Http\CrudFiles\Repositories\Interfaces\IEmailConfigurationRepository;

class EmailConfigurationController extends Controller
{
    private $viewFieldsCrud = null;

    private $routesAction = null;

    public function __construct(public IEmailConfigurationRepository $IEmailConfigurationRepository){
        $this->viewFieldsCrud = $this->IEmailConfigurationRepository->viewFields();
        $this->routesAction = $this->IEmailConfigurationRepository->actions()->toArray();
    }

    /**
     * @description get data with pagination,filter and without deleted.
     * @return mixed
     * @author moner khalil
     */
    public function index() {
        ${$this->IEmailConfigurationRepository->nameTable} = $this->IEmailConfigurationRepository->get(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get data with pagination,filter and only deleted.
     * @return mixed
     * @author moner khalil
     */
     public function indexTrashes() {
        ${$this->IEmailConfigurationRepository->nameTable} = $this->IEmailConfigurationRepository->getOnlyTrashes(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get all data without filter,pagination and deleted
     * @return mixed
     * @author moner khalil
     */
    public function indexAll(){
        ${$this->IEmailConfigurationRepository->nameTable} = $this->IEmailConfigurationRepository->get(true,false,null,false);
        return $this->responseSuccess(get_defined_vars());
    }

    public function showSearchFields(){
        $fieldsShow = $this->viewFieldsCrud->getFieldsShow();
        $fieldsSearch = $this->viewFieldsCrud->getFieldsSearch();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(get_defined_vars());
    }

    public function create() {
        $fieldsCreate = $this->viewFieldsCrud->getFieldsCreate();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(get_defined_vars());
    }

    public function store(EmailConfigurationRequest $request) {
        $item = $this->IEmailConfigurationRepository->create($request->validated());
        return $this->responseSuccess(compact("item"));
    }

    public function show($id) {
        $item = $this->IEmailConfigurationRepository->find($id,"id",null,true);
        return $this->responseSuccess(compact("item"));
    }

    public function edit($id) {
        $item = $this->IEmailConfigurationRepository->find($id,"id",null,true);
        $fieldsUpdate = $this->viewFieldsCrud->getFieldsUpdate();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(compact("item","fieldsUpdate","routesActions"));
    }

    public function update(EmailConfigurationRequest $request, $id) {
        $item = $this->IEmailConfigurationRepository->update($request->validated(),$id);
        return $this->responseSuccess(compact("item"));
    }

    public function delete($id) {
        $this->IEmailConfigurationRepository->delete($id);
        return $this->responseSuccess();
    }

    public function multiDelete(Request $request) {
        $values = $this->requestValidateIdsDelete($request, $this->IEmailConfigurationRepository->nameTable);
        $this->IEmailConfigurationRepository->multiDelete($values);
        return $this->responseSuccess();
    }

    public function forceDelete($id) {
        $this->IEmailConfigurationRepository->forceDelete($id);
        return $this->responseSuccess();
    }

    public function multiForceDelete(Request $request) {
        $values = $this->requestValidateIds($request, $this->IEmailConfigurationRepository->nameTable);
        $this->IEmailConfigurationRepository->multiForceDelete($values);
        return $this->responseSuccess();
    }

    public function restore($id) {
        $this->IEmailConfigurationRepository->restore($id);
        return $this->responseSuccess();
    }

    public function multiRestore(Request $request) {
        $values = $this->requestValidateIdsRestore($request, $this->IEmailConfigurationRepository->nameTable);
        $this->IEmailConfigurationRepository->multiRestore($values);
        return $this->responseSuccess();
    }

    public function restoreAll() {
        $this->IEmailConfigurationRepository->restoreAll();
        return $this->responseSuccess();
    }

    public function clearTrashed() {
        $this->IEmailConfigurationRepository->clearTrashes();
        return $this->responseSuccess();
    }

    public function exportXLSX(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->IEmailConfigurationRepository->exportXLSX($isEmpty,$ids);
    }

    public function exportPDF(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->IEmailConfigurationRepository->exportPDF($ids);
    }
}
