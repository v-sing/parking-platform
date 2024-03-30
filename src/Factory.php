<?php

namespace VSing\ParkingPlatform;

use VSing\ParkingPlatform\Kernel\ServiceContainer;

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

/**
 * Class Factory.
 *
 * @method static \VSing\ParkingPlatform\Parking\Application      Parking(array $config)
 * @method static \VSing\ParkingPlatform\Point\Application      Point(array $config)
 */
class Factory
{

    /**
     * @param string $name
     * @param array $config
     *
     * @return ServiceContainer
     */
    public static function make(string $name, array $config): ServiceContainer
    {
        $namespace   = Kernel\Support\Str::studly($name);
        $application = "\\VSing\\ParkingPlatform\\{$namespace}\\Application";
        ParkingPlatform::mergeConfig($config);
        return new $application($config);
    }

    /**
     * Dynamically pass methods to the application.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return ServiceContainer
     */
    public static function __callStatic(string $name, array $arguments)
    {
        return self::make($name, ...$arguments);
    }
}
