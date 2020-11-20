<?php


namespace TanerInCode\Modulity;

use Illuminate\Support\ServiceProvider;
use TanerInCode\Modulity\Commands\ControllerGenerator;
use TanerInCode\Modulity\Commands\FacadeGenerator;
use TanerInCode\Modulity\Commands\InterfaceGenerator;
use TanerInCode\Modulity\Commands\ModuleGenerator;
use TanerInCode\Modulity\Commands\ProviderGenerator;
use TanerInCode\Modulity\Commands\RepositoryGenerator;
use TanerInCode\Modulity\Commands\ServiceGenerator;

class ModulityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ( $this->app->runningInConsole() )
        {
            $this->publishes([
                __DIR__ . '/../../config/modulity.php' => config_path('modulity.php'),
            ], 'modulity.config');


            $this->commands([
                ControllerGenerator::class,
                FacadeGenerator::class,
                InterfaceGenerator::class,
                ProviderGenerator::class,
                RepositoryGenerator::class,
                ServiceGenerator::class,
                ModuleGenerator::class
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    	
    }
}
