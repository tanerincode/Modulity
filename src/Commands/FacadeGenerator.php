<?php

namespace TanerInCode\Modulity\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use TanerInCode\Modulity\Facades\ModulityFacade;

class FacadeGenerator extends Command
{
    private $makePath = '';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:facade {module} {className}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TanerInCode Module > Facade generator.';
    /**
     * @var string
     */
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
            ->setType(ModulityFacade::TYPE_IS_FACADE)
            ->setModuleName($moduleName)
            ->setClassName($className)
            ->generate();

        # return success message !
        if ( $result == 200 ){
            $this->info("Module Generator : The Facade has been created successfully");
        }else{
            $this->warn($result);
        }
    }

}
