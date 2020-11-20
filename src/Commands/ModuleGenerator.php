<?php

namespace TanerInCode\Modulity\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ModuleGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:module {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'TanerInCode Module > Directory Generator';

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
        $bar = $this->output->createProgressBar(6);
        $bar->setFormat('Progress: %current%/%max% -> <info>%message%</info>');
        $bar->setMessage('Start Generating!');
        $bar->start();


        $module = $this->argument('module');

        if ( File::isDirectory(config('modulity.module_path')."/".$module))
        {
            $bar->finish();
            $bar->clear();
            $this->warn('This module already exist!');
            return;
        }

        try {
            File::copyDirectory(__DIR__ . config('modulity.src_url') .'../setups/directories/ModuleName',config('modulity.module_path')."/".$module);
        }catch (\Exception $exception){
            $this->warn($exception->getMessage());
            return false;
        }

        $bar->setMessage('Created Directories!');
        sleep(1);

        # create main class
        Artisan::call("generate:controller", [
            'module' => $module,
            'className' => $module
        ]);

        $bar->advance();
        $bar->setMessage('Controller Created!');
        sleep(1);

        # create service
        Artisan::call("generate:service", [
            'module' => $module,
            'className' => '',
            '--first' => true
        ]);
        $bar->advance();
        $bar->setMessage('Service Created!');
        sleep(1);

        # create repository
        Artisan::call("generate:repository", [
            'module' => $module,
            'className' => '',
            '--first' => true
        ]);
        $bar->advance();
        $bar->setMessage('Repository Created!');
        sleep(1);

        # create provider
        Artisan::call("generate:provider", [
            'module' => $module,
            'className' => $module
        ]);

        $bar->advance();
        $bar->setMessage('Provider Created!');
        sleep(1);

        # create config
        Artisan::call("generate:config", [
            'module' => $module,
            'className' => $module
        ]);
        $bar->advance();
        $bar->setMessage('Config File Created!');
        sleep(1);

        # create translations
        Artisan::call("generate:translation", [
            'module' => $module,
            'className' => $module
        ]);
        $bar->advance();
        $bar->setMessage('Translation files Created!');
        sleep(1);

        $bar->setMessage("");
        $bar->finish();
        $bar->clear();

        $this->info("Module Generated !");
        return;
    }

}
