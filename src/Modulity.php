<?php


namespace TanerInCode\Modulity;


use Illuminate\Support\Facades\Artisan;
use TanerInCode\Modulity\Facades\ModulityFacade;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class Modulity
{
    const GENERATION_COMPLETE = 200;

    public $moduleName;
    public $className;
    public $modulePath;
    public $type;
    public $interface;
    public $interfaceType;
    public $first;

    protected $file_path;
    protected $setupFolder;
    protected $setupFileName;
    protected $file_type;

    public function __construct()
    {
        $this->modulePath = config('modulity.module_path');
        $this->className = 'Modulity';
        $this->moduleName = 'Modulity';
        $this->interface = false;
        $this->first = false;
        $this->interfaceType = false;
    }

    public function generate()
    {
        if ( $this->interface == true || $this->interfaceType != false ){
            $this->setType($this->type);
        }

        /**
         * Check Module Path if not exist return back error
         * */
        $modulePath = $this->modulePath."/".$this->moduleName;
        if ( ! File::isDirectory($modulePath) )
        {
            return "Module Generator : This module does not exist.";
        }

        /**
         * Generate file create path.
         * */
        $path = $modulePath.$this->file_path;
        if ( ! File::isDirectory($path) )
        {
            return "Module Generator: This ". $this->file_type ." cannot be created because structure is not suitable";
        }

        if ( $this->type == ModulityFacade::TYPE_IS_TRANSLATIONS )
        {
            return $this->runForTranslations();
        }
        if ( $this->type == ModulityFacade::TYPE_IS_CONFIG )
        {
            return $this->runForConfig();
        }

        $class = $path."/".ucfirst($this->className).$this->file_type.".php";
        if ( File::exists($class) )
        {
            return "Module Generator: This ". (is_null($this->file_type) || empty($this->file_type)) ? "config" : $this->file_type ." already exist.";
        }

        try {
            File::copy(__DIR__ . config('modulity.src_url') .'setups/'.$this->setupFolder.'/'.$this->setupFileName.'.stub',$class);
        }catch (Exception $exception){
            Log::error('module generator log', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'code' => $exception->getCode()
            ]);
            return $exception->getMessage();
        }

        if ( ! File::exists($class) )
        {
            return "Module Generator : Could not create ".$this->file_type;
        }

        $setup = File::get($class);
        $step1 = str_replace('#name_space#', config('modulity.name_space'), $setup);
        $step2 = str_replace('#Module#', $this->moduleName, $step1);
        if ( $this->first == true && ($this->type == ModulityFacade::TYPE_IS_SERVICE || $this->type == ModulityFacade::TYPE_IS_REPOSITORY) )
        {
            $step2 = str_replace(' extends Service', "", $step2);
            $step2 = str_replace(' extends Repository', "", $step2);
        }
        $lastStep = str_replace('#ClassName#', $this->className, $step2);

        try {
            File::put($class, $lastStep);
        }catch (Exception $exception){
            Log::error('Module Generator Error', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'code' => $exception->getCode()
            ]);
            return $exception->getMessage();
        }

        if ( $this->interface == true ){
            Artisan::call('generate:interface', [
                'module' => $this->moduleName,
                'className' => $this->className,
                'type' => $this->type
            ]);
        }

        return self::GENERATION_COMPLETE;
    }

    private function runForTranslations()
    {
        $pathTr = $this->modulePath."/".$this->moduleName.$this->file_path."/tr/".strtolower($this->className).".php";
        $pathEn = $this->modulePath."/".$this->moduleName.$this->file_path."/en/".strtolower($this->className).".php";

        try {
            File::copy(__DIR__ . config('modulity.src_url') .'setups/'.$this->setupFolder.'/'.$this->setupFileName.'.stub', $pathTr);
            File::copy(__DIR__ . config('modulity.src_url') .'setups/'.$this->setupFolder.'/'.$this->setupFileName.'.stub', $pathEn);
        }catch (Exception $exception){
            Log::error('module generator log', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'code' => $exception->getCode()
            ]);
            return $exception->getMessage();
        }

        return self::GENERATION_COMPLETE;
    }

    private function runForConfig()
    {
        $path = $this->modulePath."/".$this->moduleName.$this->file_path."/".strtolower($this->className).".php";

        try {
            File::copy(__DIR__ . config('modulity.src_url') .'setups/'.$this->setupFolder.'/'.$this->setupFileName.'.stub', $path);
        }catch (Exception $exception){
            Log::error('module generator log', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'code' => $exception->getCode()
            ]);
            return $exception->getMessage();
        }

        $class = $this->modulePath."/".$this->moduleName."/".$this->moduleName."ServiceProvider.php";

        $setup = File::get($class);
        $changeText = '$this->mergeConfigFrom(__DIR__."/config/'.strtolower($this->className).'.php", "'.strtolower($this->className).'");

         // #do_not_touch#';
        $replace = str_replace('// #do_not_touch#', $changeText, $setup);

        try {
            File::put($class, $replace);
        }catch (Exception $exception){
            Log::error('Module Generator Error', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
                'code' => $exception->getCode()
            ]);
            return $exception->getMessage();
        }

        return self::GENERATION_COMPLETE;
    }

    /**
     * @param mixed $type
     * @return Modulity
     */
    public function setType($type)
    {
        $this->type = $type;

        switch ($this->type)
        {
            case ModulityFacade::TYPE_IS_CONTROLLER :
                $this->file_path = "/Controllers";
                $this->setupFolder = 'classes';
                $this->setupFileName = 'controllers';
                $this->file_type = 'Controller';
            break;
            case ModulityFacade::TYPE_IS_FACADE :
                $this->file_path = "/Support/Facades";
                $this->setupFolder = 'facades';
                $this->setupFileName = 'facade';
                $this->file_type = 'Facade';
            break;
            case ModulityFacade::TYPE_IS_SERVICE :
                $this->file_path = "/Services";
                $this->setupFolder = 'services';
                if ( $this->interface == true ){
                    $this->setupFileName = 'serviceImplementByInterface';
                }else{
                    $this->setupFileName = 'service';
                }
                    $this->file_type = 'Service';
            break;
            case ModulityFacade::TYPE_IS_REPOSITORY :
                $this->file_path = "/Repositories";
                $this->setupFolder = 'repositories';
                if ( $this->interface == true ){
                    $this->setupFileName = 'repositoryImplementByInterface';
                }else{
                    $this->setupFileName = 'repository';
                }
                    $this->file_type = 'Repository';
            break;
            case ModulityFacade::TYPE_IS_INTERFACE :
                $this->setupFolder = 'interfaces';

                if ( $this->interfaceType && ($this->interfaceType == ModulityFacade::TYPE_IS_REPOSITORY || $this->interfaceType == 'R') ){
                    $this->file_path = "/Repositories";
                    $this->setupFileName = 'repository';
                    $this->file_type = 'RepositoryInterface';
                }
                elseif (  $this->interfaceType && ($this->interfaceType == ModulityFacade::TYPE_IS_SERVICE || $this->interfaceType == 'S') ) {
                    $this->file_path = "/Services";
                    $this->setupFileName = 'service';
                    $this->file_type = 'ServiceInterface';
                }
            break;
            case ModulityFacade::TYPE_IS_PROVIDER :
                $this->file_path = "/";
                $this->setupFolder = 'providers';
                $this->setupFileName = 'serviceprovider';
                $this->file_type = 'ServiceProvider';
                break;
            case ModulityFacade::TYPE_IS_CONFIG :
                $this->file_path = "/config";
                $this->setupFolder = 'config';
                $this->setupFileName = 'config';
                $this->file_type = '';
                break;
            case ModulityFacade::TYPE_IS_TRANSLATIONS :
                $this->file_path = "/Support/translations";
                $this->setupFolder = 'translations';
                $this->setupFileName = 'translation';
                $this->file_type = '';
                break;
        }

        return $this;
    }

    /**
     * @param mixed $className
     * @return Modulity
     */
    public function setClassName($className)
    {
        $this->className = $className;
        return $this;
    }

    /**
     * @param mixed $modulePath
     * @return Modulity
     */
    public function setModulePath($modulePath)
    {
        $this->modulePath = $modulePath;
        return $this;
    }

    /**
     * @param mixed $moduleName
     * @return Modulity
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
        return $this;
    }

    /**
     * @param false $interface
     * @return Modulity
     */
    public function setInterface(bool $interface)
    {
        $this->interface = $interface;
        return $this;
    }

    /**
     * @param false $first
     * @return Modulity
     */
    public function setFirst($first)
    {
        $this->first = $first;
        return $this;
    }

    /**
     * @param mixed $interfaceType
     * @return Modulity
     */
    public function setInterfaceType($interfaceType)
    {
        $this->interfaceType = $interfaceType;
        return $this;
    }

}
