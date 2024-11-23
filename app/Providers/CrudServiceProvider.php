<?php

namespace App\Providers;

use App\Helpers\MyApp;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class CrudServiceProvider extends ServiceProvider
{
    private array $ignoredFiles = ['..', '.'];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepositories();
    }

    private function registerRepositories(){
        $pathInterface = app_path("Http/CrudFiles/Repositories/Interfaces");
        if (is_dir($pathInterface)){
            $repositoriesInterfacesFile = scandir($pathInterface);

            foreach ($repositoriesInterfacesFile as $file) {

                if (!in_array($file, $this->ignoredFiles)) {
                    $file = pathinfo($file, PATHINFO_FILENAME);

                    $namespaceRepo = "App\\Http\\CrudFiles\\Repositories\\Eloquent\\" . substr($file, 1, strlen($file));

                    $this->app->bind("App\\Http\\CrudFiles\\Repositories\\Interfaces\\" . $file, $namespaceRepo);

                    $obj = app($namespaceRepo)->actions();

                    $this->registerActionsRoutes($obj);
                }
            }
        }
    }

    private function registerActionsRoutes($obj){
        $vAPI = MyApp::VersionApi;
        Route::middleware("api")
            ->prefix("api/{$vAPI}/dashboard")
            ->name("api.")
            ->group(function ()use ($obj){
                $obj->registerRoute();
            });
            #Route::middleware('web')
            #   ->group(function ()use($obj){
            #        $obj->registerRoute();
            #    });
    }
}
