<?php

namespace App\Helpers\ClassesBase;

use Illuminate\Console\Command;

class BaseCrudCommand extends Command
{
    protected function getMainNamespacesBaseClasses($file = null){
        $allBaseFiles = [
            "BaseRequest" => "App\\Helpers\\ClassesBase\\BaseRequest",
            "BaseViewFields" => "App\\Helpers\\ClassesBase\\BaseViewFields",
            "BaseResource" => "App\\Helpers\\ClassesBase\\BaseResource",
            "BaseModel" => "App\\Helpers\\ClassesBase\\Models\\BaseModel",
            "BaseTranslationModel" => "App\\Helpers\\ClassesBase\\Models\\BaseTranslationModel",
            "BaseRepository" => "App\\Helpers\\ClassesBase\\Repositories\\BaseRepository",
            "IBaseRepository" => "App\\Helpers\\ClassesBase\\Repositories\\IBaseRepository",
            "CrudActions" => "App\\Helpers\\ClassesBase\\Routes\\CrudActions",
        ];
        return is_null($file) ? $allBaseFiles : $allBaseFiles[$file];
    }

    protected function getNamespaceFoldersCrud($folderNamespace,$folder = null){
        $allFolders = [
            'repositoryInterface' => "$folderNamespace\\Http\\CrudFiles\\Repositories\\Interfaces",
            'repository' => "$folderNamespace\\Http\\CrudFiles\\Repositories\\Eloquent",
            'controller' => "$folderNamespace\\Http\\Controllers\\CrudControllers",
            'resourceApi' => "$folderNamespace\\Http\\Resources\\CrudResources",
            'request' => "$folderNamespace\\Http\\Requests\\CrudRequests",
            'observer' => "$folderNamespace\\Http\\Observers",
            'viewFields' => "$folderNamespace\\Http\\CrudFiles\\ViewFields",
            'actions' => "$folderNamespace\\Http\\CrudFiles\\Actions",
            'model' => "$folderNamespace\\Models",
            'modelTranslation' => "$folderNamespace\\Models\\Translations",
            'migration' => "$folderNamespace\\database\\migrations",
            'seeder' => "$folderNamespace\\Database\\Seeders",
        ];
        return is_null($folder) ? $allFolders : $allFolders[$folder];
    }

    protected function getNamespaceFilesCrud($model,$folderNamespace,$file = null){
        $allFiles = [
            'repositoryInterface' => "$folderNamespace\\Http\\CrudFiles\\Repositories\\Interfaces\\I{$model}Repository",
            'repository' => "$folderNamespace\\Http\\CrudFiles\\Repositories\\Eloquent\\{$model}Repository",
            'controller' => "$folderNamespace\\Http\\Controllers\\CrudControllers\\{$model}Controller",
            'resourceApi' => "$folderNamespace\\Http\\Resources\\CrudResources\\{$model}Resource",
            'request' => "$folderNamespace\\Http\\Requests\\CrudRequests\\{$model}Request",
            'observer' => "$folderNamespace\\Http\\Observers\\{$model}Observer",
            'viewFields' => "$folderNamespace\\Http\\CrudFiles\\ViewFields\\{$model}ViewFields",
            'actions' => "$folderNamespace\\Http\\CrudFiles\\Actions\\{$model}Action",
            'model' => "$folderNamespace\\Models\\{$model}",
            'modelTranslation' => "$folderNamespace\\Models\\Translations\\{$model}Translation",
            'migration' => "$folderNamespace\\database\\migrations\\{$model}",
            'seeder' => "$folderNamespace\\Database\\Seeders\\{$model}Seeder",
        ];
        return is_null($file) ? $allFiles : $allFiles[$file];
    }

    protected function getPathFilesCrud($model,$file = null,$folderNamespace = "App"){
        $folderNamespace = strtolower($folderNamespace);
        $folderNamespace = str_replace(["\\","\\\\","/","//"],"/",$folderNamespace);
        $allFiles = [];
        foreach ($this->getNamespaceFilesCrud($model,$folderNamespace) as $key => $path){
            $path = str_replace(["\\","\\\\","/","//"],"/",$path);
            $allFiles[$key] = base_path($path . ".php");
        }
        return is_null($file) ? $allFiles : $allFiles[$file];
    }

    protected function getPathStubCrudFolder($name_file){
        return base_path("stubs/crud-generator-stubs/$name_file.stub");
    }
}
