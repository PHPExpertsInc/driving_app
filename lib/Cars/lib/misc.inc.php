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

function attemptAction($class, $action, $actor, $args = null)
{
    $status = "Unsuccessfully";
    if (is_array($action))
    {
        $present_action = $action[0];
        $past_action = $action[1];
    }
    else
    {
        $present_action = $past_action = $action;
        $past_action .= 'ed';
    }

    if (DEBUG >= 2)
    {
        echo  "$class: Trying to {$present_action}.\n";
    }

    // Downshift irrationally increases the gear value.
    try
    {
        $args = (!is_null($args)) ? $args : array();
        call_user_func_array($actor, $args);
        $status = "Successfully";
    }
    catch(GearShaftException $e)
    {
        printf("BZZZ: %s\n", $e->getMessage());
    }

    if (DEBUG >= 1)
    {
        echo "$class: $status {$past_action}.\n";
    }
}

function set_debug_level()
{
    if (defined('DEBUG'))
    {
        return;
    }

    // Figure out if we're running from the command line or a web server.
    if (php_sapi_name() == 'cli')
    {
        $args = getopt('', array('debug::'));

        if (isset($args['debug']))
        {
            $debug_level = $args['debug'];
        }
    }
    else
    {
        if (isset($_COOKIE['debug']))
        {
            $debug_level = filter_input(INPUT_COOKIE, 'debug', FILTER_SANITIZE_NUMBER_INT);
        }

        if (isset($_GET['debug']))
        {
            $debug_level = filter_input(INPUT_GET, 'debug', FILTER_SANITIZE_NUMBER_INT);
        }
    }

    if (!isset($debug_level))
    {
        $debug_level = 0;
    }

    define('DEBUG', $debug_level);
}

function filter_files_by_type(&$files, $type)
{
    $count = count($files);
    for ($a = 0; $a < $count; ++$a)
    {
        if (preg_match("/\.$type\.php\$/", $files[$a]) === 0)
        {
            // Kill the item.
            unset($files[$a]);
        }
    }

    sort($files);
}

function autoload_car_classes($className)
{
    static $classes = null;

    // Declare the types of classes this project has.
    $classTypes = array('interface' => CARS_LIB_PATH . '/interfaces', 
                        'abstract'  => CARS_LIB_PATH . '/scaffolds', 
                        'part'      => CARS_LIB_PATH . '/parts',
                        'stats'     => CARS_LIB_PATH . '/models');

    // Cache classes.
    if (is_null($classes))
    {
        // Find all the files for these class types.
        $classes = array();
        foreach ($classTypes as $type => $directory)
        {
            $files = scandir($directory);
            filter_files_by_type($files, $type);
            foreach ($files as $file)
            {
                if (($pos = strpos($file, ".$type.php")) !== false)
                {
                    $class = substr($file, 0, $pos);
                    $classes[$class] = $directory . '/' . $file;
                }
            }
        }
    }

    if (isset($classes[$className]))
    {
        require $classes[$className];
    }
//    echo $classes[$className]; exit;
}
