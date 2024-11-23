<?php

namespace App\Console\Commands;

use App\Helpers\ClassesBase\BaseCrudCommand;
use App\Helpers\MyApp;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CrudDestroyCommand extends BaseCrudCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:destroy {model*} {--namespace=App}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'destroy all files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $namespace = $this->option("namespace");
        $namespace = str_replace(["\\\\","\\","/","//"],"\\",$namespace);
        $namespace = explode("\\",$namespace);
        $namespace = collect($namespace)->map(function ($TValue, $TKey){
            return ucfirst($TValue);
        })->toArray();
        $namespace = implode("\\",$namespace);
        $models = $this->argument("model");
        foreach ($models as $model){
            $isDeleteAnyThing = false;
            $model =MyApp::Classes()->stringProcess->camelCase($model);
            $files = $this->getNamespaceFilesCrud($model,$namespace);
            foreach ($files as $key => $file){
                $file = base_path($file);
                if ($key == "seeder" && $namespace == "App"){
                    $file = base_path($this->getNamespaceFilesCrud($model,"","seeder"));
                }
                $file .= ".php";
                if (File::exists($file) && File::isFile($file)) {
                    $isDeleteAnyThing = true;
                    File::delete($file);
                }
            }
            #Folder Views Delete Code..
            #...
            #Final..
            if (!$isDeleteAnyThing) {
                $this->error("the crud $model not found -_-");
            } else {
                $this->info("The crud files have been successfully deleted.");
                $this->line("");
                $this->warn("Please delete migration file.");
            }
        }
        return true;
    }
}
