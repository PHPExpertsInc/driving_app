<?php

require 'lib/misc.inc.php';

define('CARS_LIB_PATH', dirname(__FILE__));

// FIXME: Temp fix.
function individual_cars_autoloader($className)
{
    if (($pos = strpos($className, "Car")) !== false)
    {
        $filename = CARS_LIB_PATH . "/" . substr($className, 0, $pos) . ".car.php";

        require $filename;            
    }
}

set_debug_level();
spl_autoload_register('autoload_car_classes');
spl_autoload_register('individual_cars_autoloader');
