<?php

require 'lib/misc.inc.php';

define('CARS_LIB_PATH', dirname(__FILE__));

set_debug_level();
spl_autoload_register('autoload_car_classes');
