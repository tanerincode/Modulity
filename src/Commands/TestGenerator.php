<?php

namespace TanerInCode\Modulity\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use TanerInCode\Modulity\Facades\ModulityFacade;

class TestGenerator extends Command
{
    private $makePath = '';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:test {module} {className} {--U|unit} {--F|feature}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TanerInCode Module > Test  Generator';
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
        $feature  = $this->option('feature');
        $testType = ($feature) ? ModulityFacade::TEST_TYPE_IS_UNIT : ModulityFacade::TEST_TYPE_IS_FEATURE;

        $result = ModulityFacade::setModulePath(config('modulity.module_path'))
            ->setModuleName($moduleName)
            ->setClassName($className)
            ->setType(ModulityFacade::TYPE_IS_TEST)
            ->setTestType($testType)
            ->generate();

        if ( $result == 200 ){
            $this->info("Module Generator : The Test has been created successfully");
        }else{
            $this->warn($result);
        }
    }
}
