<?php

namespace App\Http\Controllers\CrudControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CrudRequests\SocialMediaRequest;
use App\Http\CrudFiles\Repositories\Interfaces\ISocialMediaRepository;

class SocialMediaController extends Controller
{
    private $viewFieldsCrud = null;

    private $routesAction = null;

    public function __construct(public ISocialMediaRepository $ISocialMediaRepository){
        $this->viewFieldsCrud = $this->ISocialMediaRepository->viewFields();
        $this->routesAction = $this->ISocialMediaRepository->actions()->toArray();
    }

    /**
     * @description get data with pagination,filter and without deleted.
     * @return mixed
     * @author moner khalil
     */
    public function index() {
        ${$this->ISocialMediaRepository->nameTable} = $this->ISocialMediaRepository->get(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get data with pagination,filter and only deleted.
     * @return mixed
     * @author moner khalil
     */
     public function indexTrashes() {
        ${$this->ISocialMediaRepository->nameTable} = $this->ISocialMediaRepository->getOnlyTrashes(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get all data without filter,pagination and deleted
     * @return mixed
     * @author moner khalil
     */
    public function indexAll(){
        ${$this->ISocialMediaRepository->nameTable} = $this->ISocialMediaRepository->get(true,false,null,false);
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

    public function store(SocialMediaRequest $request) {
        $item = $this->ISocialMediaRepository->create($request->validated());
        return $this->responseSuccess(compact("item"));
    }

    public function show($id) {
        $item = $this->ISocialMediaRepository->find($id,"id",null,true);
        return $this->responseSuccess(compact("item"));
    }

    public function edit($id) {
        $item = $this->ISocialMediaRepository->find($id,"id",null,true);
        $fieldsUpdate = $this->viewFieldsCrud->getFieldsUpdate();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(compact("item","fieldsUpdate","routesActions"));
    }

    public function update(SocialMediaRequest $request, $id) {
        $item = $this->ISocialMediaRepository->update($request->validated(),$id);
        return $this->responseSuccess(compact("item"));
    }

    public function delete($id) {
        $this->ISocialMediaRepository->delete($id);
        return $this->responseSuccess();
    }

    public function multiDelete(Request $request) {
        $values = $this->requestValidateIdsDelete($request, $this->ISocialMediaRepository->nameTable);
        $this->ISocialMediaRepository->multiDelete($values);
        return $this->responseSuccess();
    }

    public function forceDelete($id) {
        $this->ISocialMediaRepository->forceDelete($id);
        return $this->responseSuccess();
    }

    public function multiForceDelete(Request $request) {
        $values = $this->requestValidateIds($request, $this->ISocialMediaRepository->nameTable);
        $this->ISocialMediaRepository->multiForceDelete($values);
        return $this->responseSuccess();
    }

    public function restore($id) {
        $this->ISocialMediaRepository->restore($id);
        return $this->responseSuccess();
    }

    public function multiRestore(Request $request) {
        $values = $this->requestValidateIdsRestore($request, $this->ISocialMediaRepository->nameTable);
        $this->ISocialMediaRepository->multiRestore($values);
        return $this->responseSuccess();
    }

    public function restoreAll() {
        $this->ISocialMediaRepository->restoreAll();
        return $this->responseSuccess();
    }

    public function clearTrashed() {
        $this->ISocialMediaRepository->clearTrashes();
        return $this->responseSuccess();
    }

    public function exportXLSX(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->ISocialMediaRepository->exportXLSX($isEmpty,$ids);
    }

    public function exportPDF(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->ISocialMediaRepository->exportPDF($ids);
    }
}
