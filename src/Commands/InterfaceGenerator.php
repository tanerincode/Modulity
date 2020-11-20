<?php

namespace TanerInCode\Modulity\Commands;

use Illuminate\Console\Command;
use TanerInCode\Modulity\Facades\ModulityFacade;

class InterfaceGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:interface {module} {className} {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TanerInCode Module > Interface Generator';

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
        $interfaceType = $this->argument("type");


        $result = ModulityFacade::setModulePath(config('modulity.module_path'))
            ->setModuleName($moduleName)
            ->setClassName($className)
            ->setType(ModulityFacade::TYPE_IS_INTERFACE)
            ->setInterfaceType($interfaceType)
            ->generate();

        # return success message !
        if ( $result == 200 ){
            $this->info("Module Generator : The Interface has been created successfully");
        }else{
            $this->warn($result);
        }
        return true;
    }
}
