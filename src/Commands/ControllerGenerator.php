<?php

namespace TanerInCode\Modulity\Commands;

use TanerInCode\Modulity\Facades\ModulityFacade;
use Illuminate\Console\Command;

class ControllerGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     * module : Oluşturulacak classın hangi modulun içinde olacağını belirler.
     * className : Oluşturulacak classın adını belirler. interface adı otomatik olarak tanımlanır.
     */
    protected $signature = 'generate:controller {module} {className}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TanerInCode Module > Class Generator';
    private $MakeError;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        # get arguments
        $moduleName = $this->argument('module');
        $className = $this->argument('className');

        $result = ModulityFacade::setModulePath(config('modulity.module_path'))
                            ->setType(ModulityFacade::TYPE_IS_CONTROLLER)
                            ->setModuleName($moduleName)
                            ->setClassName($className)
                            ->generate();

        # return success message !
        if ( $result == 200 ){
            $this->info("Module Generator : The controller has been created successfully");
        }else{
            $this->warn($result);
        }
    }
}
