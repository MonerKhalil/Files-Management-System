<?php

namespace App\Console\Commands;

use App\Helpers\ClassesBase\BaseCrudCommand;
use App\Helpers\MyApp;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CrudGenerateCommand extends BaseCrudCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate {model*} {--namespace=App} {--translation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'crud-generate-create -> {model == name files}+,{--namespace files and created}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isTranslation = (boolean)$this->option("translation");
        $namespace = $this->option("namespace");
        $namespace = str_replace(["\\\\","\\","/","//"],"\\",$namespace);
        $namespace = explode("\\",$namespace);
        $namespace = collect($namespace)->map(function ($TValue, $TKey){
            return ucfirst($TValue);
        })->toArray();
        $namespace = implode("\\",$namespace);
        $models = $this->argument("model");
        foreach ($models as $model){
            //"test_string" => "TestString"
            $this->info("==========================================");
            $mainModel = $model;
            $model = MyApp::Classes()->stringProcess->camelCase($model);
            $modelAsKebab = Str::kebab($model);
            #model-migration-seeder
            $this->info('model,migration,seeder and request file crud to be generated...');
            if (!$isTranslation){
                $this->createModelFile($mainModel,$model,$modelAsKebab,$namespace);
            }else{
                $this->createModelTranslationFile($mainModel,$model,$modelAsKebab,$namespace);
            }
            $this->createSeederFile($model,$namespace);
//            $this->createObserverFile($model,$namespace);
            $this->createRequestFile($model,$namespace);
            #repository
            $this->info('repository file crud to be generated...');
            $this->createRepositoryFile($model,$namespace);
            #view-fields
            $this->info('viewField file crud to be generated...');
            $this->createViewFieldsFile($model,$namespace);
            #controller
            $this->info('controller file crud to be generated...');
            $this->createControllerFile($model,$namespace,$isTranslation);
            $this->createActionFile($model,$namespace);
            #route
            #$this->addRouteCrud($model,$modelAsKebab,$namespace);
            $this->info("==================generated-files-$model========================");
        }
        return true;
    }

    /**
     * @param $mainModel
     * @param $model
     * @param $modelAsKebab
     * @param $namespace
     * @author moner khalil
     */
    protected function createModelFile($mainModel,$model, $modelAsKebab, $namespace){
        $modelFilePath = $this->getPathStubCrudFolder("model");
        $modelFile = File::get($modelFilePath);
        $modelFile = str_replace("{{-model-}}", $model, $modelFile);
        $modelFile = str_replace("{{model-asKebab}}", $modelAsKebab, $modelFile);
        $modelFile = str_replace("{{namespace-File}}", $this->getNamespaceFoldersCrud($namespace,"model"), $modelFile);
        $modelFile = str_replace("{{namespace-BaseModel}}", $this->getMainNamespacesBaseClasses("BaseModel"), $modelFile);
        File::put($this->getPathFilesCrud($model, 'model',$namespace), $modelFile);
        #migration-file
        $nameTable = Str::plural($mainModel);
        $nameTable = strtolower($nameTable);
        $timestamp = now()->format('Y_m_d_His');
        $nameFile = $timestamp."_create_".$nameTable."_table";
        $migrateFilePath = $this->getPathStubCrudFolder("migration");
        $migrateFile = File::get($migrateFilePath);
        $migrateFile = str_replace("{{table-name}}", $nameTable, $migrateFile);
        File::put($this->getPathFilesCrud($nameFile, 'migration',$namespace == "App" ? "" : $namespace), $migrateFile);
    }

    /**
     * @param $mainModel
     * @param $model
     * @param $modelAsKebab
     * @param $namespace
     * @author moner khalil
     */
    protected function createModelTranslationFile($mainModel,$model, $modelAsKebab, $namespace){
        $nameTable = Str::plural($mainModel);
        $nameTable = strtolower($nameTable);
        $timestamp = now()->format('Y_m_d_His');
        $nameFile = $timestamp."_create_".$nameTable."_table";
        $nameFileTranslation = $timestamp."_create_".$nameTable."_translations_table";
        #model
        $modelFilePath = $this->getPathStubCrudFolder("model.translation");
        $modelFile = File::get($modelFilePath);
        $modelFile = str_replace("{{-model-}}", $model, $modelFile);
        $modelFile = str_replace("{{model-asKebab}}", $modelAsKebab, $modelFile);
        $modelFile = str_replace("{{namespace-File}}", $this->getNamespaceFoldersCrud($namespace,"model"), $modelFile);
        $modelFile = str_replace("{{namespace-BaseTranslationModel}}", $this->getMainNamespacesBaseClasses("BaseTranslationModel"), $modelFile);
        $modelFile = str_replace("{{namespace-ModelTranslation}}", $this->getNamespaceFilesCrud($model,$namespace,"modelTranslation"), $modelFile);
        $modelFile = str_replace("{{fk_relation_id}}",MyApp::Classes()->languageProcess->getFkMainTableInTranslationTable(), $modelFile);
        File::put($this->getPathFilesCrud($model, 'model',$namespace), $modelFile);
        #model-translation
        $modelTranslationFilePath = $this->getPathStubCrudFolder("modelTranslation");
        $modelTranslationFile = File::get($modelTranslationFilePath);
        $modelTranslationFile = str_replace("{{-model-}}", $model, $modelTranslationFile);
        $modelTranslationFile = str_replace("{{table-name}}", $nameTable, $modelTranslationFile);
        $modelTranslationFile = str_replace("{{namespace-File}}",$this->getNamespaceFoldersCrud($namespace,"modelTranslation") , $modelTranslationFile);
        File::put($this->getPathFilesCrud($model, 'modelTranslation',$namespace), $modelTranslationFile);
        #migration-file
        $migrateFilePath = $this->getPathStubCrudFolder("migration");
        $migrateFile = File::get($migrateFilePath);
        $migrateFile = str_replace("{{table-name}}", $nameTable, $migrateFile);
        File::put($this->getPathFilesCrud($nameFile, 'migration',$namespace == "App" ? "" : $namespace), $migrateFile);
        #migration-translation-file
        $migrateFilePath = $this->getPathStubCrudFolder("migration.translation");
        $migrateFile = File::get($migrateFilePath);
        $migrateFile = str_replace("{{table-name}}", $nameTable, $migrateFile);
        $migrateFile = str_replace("{{language_id}}", MyApp::Classes()->languageProcess->getFkLanguageInTranslationTable(), $migrateFile);
        $migrateFile = str_replace("{{row_main_id}}", MyApp::Classes()->languageProcess->getFkMainTableInTranslationTable(), $migrateFile);
        File::put($this->getPathFilesCrud($nameFileTranslation, 'migration',$namespace == "App" ? "" : $namespace), $migrateFile);
    }

    /**
     * @param string $model
     * @param string $namespace
     */
    protected function createSeederFile(string $model, string $namespace){
        $seederFilePath = $this->getPathStubCrudFolder("seeder");
        $seederFile = File::get($seederFilePath);
        $namespaceFile = $this->getNamespaceFoldersCrud($namespace == "App" ? "" : $namespace,"seeder");
        $namespaceFile = ltrim($namespaceFile,"\\");
        $seederFile = str_replace("{{namespace-File}}", $namespaceFile, $seederFile);
        $seederFile = str_replace("{{-model-}}", $model, $seederFile);
        File::put($this->getPathFilesCrud($model, 'seeder',$namespace == "App" ? "" : $namespace), $seederFile);
    }

    protected function createObserverFile(string $model, string $namespace){
        $observerFilePath = $this->getPathStubCrudFolder("observer");
        $observerFile = File::get($observerFilePath);
        $observerFile = str_replace("{{namespace-File}}", $this->getNamespaceFoldersCrud($namespace,"observer"), $observerFile);
        $observerFile = str_replace("{{-model-}}", $model, $observerFile);
        $observerFile = str_replace("{{namespace-Model}}", $this->getNamespaceFilesCrud($model,$namespace,"model"), $observerFile);
        File::put($this->getPathFilesCrud($model, 'observer',$namespace), $observerFile);
    }

    protected function createRequestFile(string $model, string $namespace){
        $requestFilePath = $this->getPathStubCrudFolder("request");
        $requestFile = File::get($requestFilePath);
        $requestFile = str_replace("{{namespace-File}}", $this->getNamespaceFoldersCrud($namespace,"request"), $requestFile);
        $requestFile = str_replace("{{-model-}}", $model, $requestFile);
        $requestFile = str_replace("{{namespace-BaseRequest}}", $this->getMainNamespacesBaseClasses("BaseRequest"), $requestFile);
        File::put($this->getPathFilesCrud($model, 'request',$namespace), $requestFile);
    }
    /**
     * @param string $model
     * @param string $namespace
     * @author moner khalil
     */
    protected function createRepositoryFile(string $model,string $namespace)
    {
        #InterfaceRepository
        $interfaceFilePath = $this->getPathStubCrudFolder("repository.interface");
        $interfaceFile = File::get($interfaceFilePath);
        $interfaceFile = str_replace("{{-model-}}", $model, $interfaceFile);
        $interfaceFile = str_replace("{{namespace-File}}", $this->getNamespaceFoldersCrud($namespace,"repositoryInterface"), $interfaceFile);
        $interfaceFile = str_replace("{{namespace-IBaseRepository}}", $this->getMainNamespacesBaseClasses("IBaseRepository"), $interfaceFile);
        File::put($this->getPathFilesCrud($model, 'repositoryInterface',$namespace), $interfaceFile);
        #Repository
        $repositoryFilePath = $this->getPathStubCrudFolder("repository.eloquent");
        $repositoryFile = File::get($repositoryFilePath);
        $repositoryFile = str_replace("{{-model-}}", $model, $repositoryFile);
        $repositoryFile = str_replace("{{namespace-File}}", $this->getNamespaceFoldersCrud($namespace,"repository"), $repositoryFile);
        $repositoryFile = str_replace("{{namespace-ModelIRepository}}", $this->getNamespaceFilesCrud($model,$namespace,"repositoryInterface"), $repositoryFile);
        $repositoryFile = str_replace("{{namespace-ModelViewFields}}", $this->getNamespaceFilesCrud($model,$namespace,"viewFields"), $repositoryFile);
        $repositoryFile = str_replace("{{namespace-Model}}", $this->getNamespaceFilesCrud($model,$namespace,"model"), $repositoryFile);
        $repositoryFile = str_replace("{{namespace-Actions}}", $this->getNamespaceFilesCrud($model,$namespace,"actions"), $repositoryFile);
        $repositoryFile = str_replace("{{namespace-BaseRepository}}", $this->getMainNamespacesBaseClasses("BaseRepository"), $repositoryFile);
        $repositoryFile = str_replace("{{namespace-BaseViewFields}}", $this->getMainNamespacesBaseClasses("BaseViewFields"), $repositoryFile);
        $repositoryFile = str_replace("{{namespace-BaseActions}}", $this->getMainNamespacesBaseClasses("CrudActions"), $repositoryFile);
        File::put($this->getPathFilesCrud($model, 'repository',$namespace), $repositoryFile);
    }

    public function createViewFieldsFile($model,$namespace){
        $viewFieldsFilePath = $this->getPathStubCrudFolder("view.fields");
        $viewFieldsFile = File::get($viewFieldsFilePath);
        $viewFieldsFile = str_replace("{{-model-}}", $model, $viewFieldsFile);
        $viewFieldsFile = str_replace("{{namespace-File}}", $this->getNamespaceFoldersCrud($namespace,"viewFields"), $viewFieldsFile);
        $viewFieldsFile = str_replace("{{namespace-ModelRepository}}", $this->getNamespaceFilesCrud($model,$namespace,"repository"), $viewFieldsFile);
        $viewFieldsFile = str_replace("{{namespace-BaseViewFields}}", $this->getMainNamespacesBaseClasses("BaseViewFields"), $viewFieldsFile);
        File::put($this->getPathFilesCrud($model, 'viewFields',$namespace), $viewFieldsFile);
    }

    public function createControllerFile($model,$namespace,$isTranslation){
        $controllerFilePath = $this->getPathStubCrudFolder("controller");
        $controllerFile = File::get($controllerFilePath);
        $controllerFile = str_replace("{{-model-}}", $model, $controllerFile);
        $controllerFile = str_replace("{{namespace-File}}", $this->getNamespaceFoldersCrud($namespace,"controller"), $controllerFile);
        $controllerFile = str_replace("{{namespace-Request}}", $this->getNamespaceFilesCrud($model,$namespace,"request"), $controllerFile);
        $controllerFile = str_replace("{{namespace-IRepository}}", $this->getNamespaceFilesCrud($model,$namespace,"repositoryInterface"), $controllerFile);
        $controllerFile = str_replace("{{-function-edit-}}", $isTranslation ? 'find($id,"id",null,true,true,true)' : 'find($id,"id",null,true)', $controllerFile);
        File::put($this->getPathFilesCrud($model, 'controller',$namespace), $controllerFile);
    }

    public function createActionFile($model,$namespace){
        $actionFilePath = $this->getPathStubCrudFolder("actions");
        $actionFile = File::get($actionFilePath);
        $actionFile = str_replace("{{-model-}}", $model, $actionFile);
        $actionFile = str_replace("{{namespace-File}}", $this->getNamespaceFoldersCrud($namespace,"actions"), $actionFile);
        $actionFile = str_replace("{{namespace-BaseCrudActions}}",$this->getMainNamespacesBaseClasses("CrudActions"), $actionFile);
        $actionFile = str_replace("{{namespace-Controller}}", $this->getNamespaceFilesCrud($model,$namespace,"controller"), $actionFile);
        File::put($this->getPathFilesCrud($model, 'actions',$namespace), $actionFile);
    }

    public function addRouteCrud($model,$modelAsKebab,$namespace){
        $routePath = base_path('routes/crud-routes/routes.php');
        $routeFile = File::get($routePath);
        $nameController = "{$model}Controller";
        $strNamespace = "##############################################-namespace-##############################################";
        $strNamespaceFinal = $strNamespace . "\nuse " . $this->getNamespaceFoldersCrud($namespace,"controller") . "\\$nameController;";
        $strRoute = "################################################-routes-################################################";
        $strRouteFinal = $strRoute . "\nMyApp::Classes()->routeProcess->RoutesCrud('{$modelAsKebab}',$nameController::class);";
        $routeFile = str_replace($strNamespace,$strNamespaceFinal,$routeFile);
        $routeFile = str_replace($strRoute,$strRouteFinal,$routeFile);
        File::put($routePath,$routeFile);
    }

}
