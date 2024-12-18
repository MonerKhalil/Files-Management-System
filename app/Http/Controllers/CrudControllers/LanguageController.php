<?php

namespace App\Http\Controllers\CrudControllers;

use App\Http\Controllers\Controller;
use App\Services\LanguageService;
use Illuminate\Http\Request;
use App\Http\Requests\CrudRequests\LanguageRequest;
use App\Http\CrudFiles\Repositories\Interfaces\ILanguageRepository;

class LanguageController extends Controller
{
    private $viewFieldsCrud = null;

    private $routesAction = null;

    public function __construct(public ILanguageRepository $ILanguageRepository){
        $this->viewFieldsCrud = $this->ILanguageRepository->viewFields();
        $this->routesAction = $this->ILanguageRepository->actions()->toArray();
    }

    /**
     * @description get data with pagination,filter and without deleted.
     * @return mixed
     * @author moner khalil
     */
    public function index() {
        ${$this->ILanguageRepository->nameTable} = $this->ILanguageRepository->get(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get data with pagination,filter and only deleted.
     * @return mixed
     * @author moner khalil
     */
     public function indexTrashes() {
        ${$this->ILanguageRepository->nameTable} = $this->ILanguageRepository->getOnlyTrashes(false,true,null,true);
        return $this->responseSuccess(get_defined_vars());
    }

    /**
     * @description get all data without filter,pagination and deleted
     * @return mixed
     * @author moner khalil
     */
    public function indexAll(){
        ${$this->ILanguageRepository->nameTable} = $this->ILanguageRepository->get(true,false,null,false);
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

    public function store(LanguageRequest $request,LanguageService $languageService) {
        $item = $languageService->createLanguage($request->validated());
        return $this->responseSuccess(compact("item"));
    }

    public function show($id) {
        $item = $this->ILanguageRepository->find($id,"id",null,true);
        return $this->responseSuccess(compact("item"));
    }

    public function edit($id) {
        $item = $this->ILanguageRepository->find($id,"id",null,true);
        $fieldsUpdate = $this->viewFieldsCrud->getFieldsUpdate();
        $routesActions = $this->routesAction;
        return $this->responseSuccess(compact("item","fieldsUpdate","routesActions"));
    }

    public function update(LanguageRequest $request, $id,LanguageService $languageService) {
        $item = $languageService->updateLanguage($request->validated(),$id);
        return $this->responseSuccess(compact("item"));
    }

    public function delete($id) {
        $this->ILanguageRepository->delete($id,"id",true,function ($q){
            return $q->where("default",false);
        });
        return $this->responseSuccess();
    }

    public function multiDelete(Request $request) {
        $values = $this->requestValidateIdsDelete($request, $this->ILanguageRepository->nameTable);
        $this->ILanguageRepository->multiDelete($values,"id",true,function ($q){
            return $q->where("default",false);
        });
        return $this->responseSuccess();
    }

    public function forceDelete($id) {
        $this->ILanguageRepository->forceDelete($id,"id",true,function ($q){
            return $q->where("default",false);
        });
        return $this->responseSuccess();
    }

    public function multiForceDelete(Request $request) {
        $values = $this->requestValidateIds($request, $this->ILanguageRepository->nameTable);
        $this->ILanguageRepository->multiForceDelete($values,"id",true,function ($q){
            return $q->where("default",false);
        });
        return $this->responseSuccess();
    }

    public function restore($id) {
        $this->ILanguageRepository->restore($id);
        return $this->responseSuccess();
    }

    public function multiRestore(Request $request) {
        $values = $this->requestValidateIdsRestore($request, $this->ILanguageRepository->nameTable);
        $this->ILanguageRepository->multiRestore($values);
        return $this->responseSuccess();
    }

    public function restoreAll() {
        $this->ILanguageRepository->restoreAll();
        return $this->responseSuccess();
    }

    public function clearTrashed() {
        $this->ILanguageRepository->clearTrashes();
        return $this->responseSuccess();
    }

    public function exportXLSX(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->ILanguageRepository->exportXLSX($isEmpty,$ids);
    }

    public function exportPDF(Request $request){
        list($isEmpty,$ids) = $this->getParametersInExportFunctions($request);
        return $this->ILanguageRepository->exportPDF($ids);
    }
}
