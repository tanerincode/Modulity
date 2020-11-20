<?php

namespace TanerInCode\Modulity\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use TanerInCode\Modulity\Facades\ModulityFacade;

class RepositoryGenerator extends Command
{
    private $makePath = '';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:repository {module} {className} {--I|interface} {--F|first}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TanerInCode Module > Repository generator.';
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
        $moduleName = $this->argument('module');
        $className = $this->argument('className');
        $withInterFace  = $this->option('interface');
        $firstTime  = $this->option('first');

        $result = ModulityFacade::setModulePath(config('modulity.module_path'))
            ->setModuleName($moduleName)
            ->setClassName($className)
            ->setInterface($withInterFace)
            ->setFirst($firstTime)
            ->setType(ModulityFacade::TYPE_IS_REPOSITORY)
            ->generate();

        # return success message !
        if ( $result == 200 ){
            $this->info("Module Generator : The Repository has been created successfully");
        }else{
            $this->warn($result);
        }
    }
}
