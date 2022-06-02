<?php


namespace TanerInCode\Modulity\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static setClassName(string $className)
 * @method static setModuleName(string $moduleName)
 * @method static setModulePath(string $modulePath)
 * @method static setFirst(bool $first)
 * @method static setInterface(bool $interface)
 * @method static setTestType(string $testType = "Unit")
 * @method static generate()
 */
class ModulityFacade extends Facade
{
    public const TYPE_IS_CONTROLLER = 'controller';
    public const TYPE_IS_FACADE = 'facades';
    public const TYPE_IS_SERVICE = 'service';
    public const TYPE_IS_REPOSITORY = 'repository';
    public const TYPE_IS_INTERFACE = 'interface';
    public const TYPE_IS_PROVIDER = 'provider';
    public const TYPE_IS_CONFIG = 'config';
    public const TYPE_IS_TRANSLATIONS = 'translation';
    public const TYPE_IS_TEST = 'tests';

    public const TEST_TYPE_IS_UNIT = 'Unit';
    public const TEST_TYPE_IS_FEATURE = 'Feature';

    protected static function getFacadeAccessor()
    {
        return "ModulityFacade";
    }
}
