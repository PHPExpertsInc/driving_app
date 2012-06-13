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

include 'misc/misc.inc.php';
include 'misc/CarFactory.inc.php';
include 'misc/CarStatsManager.inc.php';


// Singleton pattern
class Cars
{
    private static $instance = null;

    private function __construct()
    {
        ob_start();

        define('CARS_LIB_PATH', dirname(__FILE__));
        convert_command_line_to_get();

        if (isset($_GET['help']) || !isset($_GET['car']))
        {
            show_help();
        }

        set_debug_level();
        spl_autoload_register('autoload_car_classes');
        spl_autoload_register(array('Car', 'autoloader'));
    }

    public function __destruct()
    {
        // Detect if we're seeing this via a web server.
        if (php_sapi_name() != 'cli' && isset($_SERVER))
        {
            // Crazy hack to output either HTML or text based on context.
            $text = ob_get_clean();
            $html = $this->convertTextToHTML($text);
            echo $html;
        }
    }

    private function convertTextToHTML($text)
    {
        $html = <<<HTML
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Cars</title>
    </head>
    <body>
        <h1>PHP Car App</h1>
        <p style="white-space: pre">
            $text
        </p>
    </body>
</html>
HTML;

        return $html;
    }

    public static function init()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new Cars;
        }

        return self::$instance;
    }
}


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
