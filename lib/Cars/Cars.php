<?php
/**
* Driving App
*   Copyright © 2010 Theodore R. Smith <theodore@phpexperts.pro>
* 
* The following code is licensed under a modified BSD License.
* All of the terms and conditions of the BSD License apply with one
* exception:
*
* 1. Every one who has not been a registered student of the "PHPExperts
*    From Beginner To Pro" course (http://www.phpexperts.pro/) is forbidden
*    from modifing this code or using in an another project, either as a
*    deritvative work or stand-alone.
*
* BSD License: http://www.opensource.org/licenses/bsd-license.php
**/

require 'lib/misc.inc.php';

define('CARS_LIB_PATH', dirname(__FILE__));

set_debug_level();
spl_autoload_register('autoload_car_classes');
spl_autoload_register(array('Car', 'autoloader'));
/*
               /\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\
              /\/\/  \/  \/  \/  \/  \/  \/  \/\/\
             /\  /\/\/    \/\/    \/\/    \/\/\  /\
            /\/\/\/\/      \/      \/      \/\/\/\/\
           /\      /\/\/\/\/        \/\/\/\/\      /\
          /\/\    /\/\/  \/          \/  \/\/\    /\/\
         /\  /\  /\  /\/\/            \/\/\  /\  /\  /\
        /\/\/\/\/\/\/\/\/              \/\/\/\/\/\/\/\/\
       /\              /\/\/\/\/\/\/\/\/\              /\
      /\/\            /\/\/  \/  \/  \/\/\            /\/\
     /\  /\          /\  /\/\/    \/\/\  /\          /\  /\
    /\/\/\/\        /\/\/\/\/      \/\/\/\/\        /\/\/\/\
   /\      /\      /\      /\/\/\/\/\      /\      /\      /\
  /\/\    /\/\    /\/\    /\/\/  \/\/\    /\/\    /\/\    /\/\
 /\  /\  /\  /\  /\  /\  /\  /\/\/\  /\  /\  /\  /\  /\  /\  /\
/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\
\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/
 \/  \/  \/  \/  \/  \/  \/  \/\/\/  \/  \/  \/  \/  \/  \/  \/
  \/\/    \/\/    \/\/    \/\/\  /\/\/    \/\/    \/\/    \/\/
   \/      \/      \/      \/\/\/\/\/      \/      \/      \/
    \/\/\/\/        \/\/\/\/\      /\/\/\/\/        \/\/\/\/
     \/  \/          \/  \/\/\    /\/\/  \/          \/  \/
      \/\/            \/\/\  /\  /\  /\/\/            \/\/
       \/              \/\/\/\/\/\/\/\/\/              \/
        \/\/\/\/\/\/\/\/\              /\/\/\/\/\/\/\/\/
         \/  \/  \/  \/\/\            /\/\/  \/  \/  \/
          \/\/    \/\/\  /\          /\  /\/\/    \/\/
           \/      \/\/\/\/\        /\/\/\/\/      \/
            \/\/\/\/\      /\      /\      /\/\/\/\/
             \/  \/\/\    /\/\    /\/\    /\/\/  \/
              \/\/\  /\  /\  /\  /\  /\  /\  /\/\/
               \/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/\/
*/