<?php
namespace MAM;

use MAM\Services\DB\DB;
use MAM\Services\API\API;
use MAM\Services\Import\Import;

final class Init
{
    /**
     * Store all the classes inside an array
     * @return array Full list of classes
     */
    public static function get_services()
    {
        return [
            DB::class,
            Import::class,
            API::class
        ];
    }

    /**
     * Loop through the classes, initialize them,
     * and call the register() method if it exists
     * @return void
     */
    public static function register_services()
    {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    /**
     * Initialize the class
     * @param  string $class    class from the services array
     * @return object instance new instance of the class
     */
    private static function instantiate($class)
    {
        return new $class();
    }
}