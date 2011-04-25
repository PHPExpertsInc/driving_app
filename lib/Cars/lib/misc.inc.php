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
    else if (is_string($action))
    {
        $present_action = $past_action = $action;
        $past_action .= 'ed';
    }

    if (DEBUG >= 2 && !is_null($action))
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

    if (DEBUG >= 1 && !is_null($action))
    {
        echo "$class: $status {$past_action}.\n";
    }
}

function convert_command_line_to_get()
{
    if (php_sapi_name() == 'cli')
    {
        $_GET = getopt('', array('help', 'car:', 'debug:'));
    }    
}

function show_help()
{
?>

Cars 1.0

Mandatory parameters:
   --car=MAKEMODEL   set the car model MakeModel

Optional parameters:
   --help            show this help screen
   --debug=LEVEL     set the debug level

<?php
	if (php_sapi_name() != 'cli' && isset($_SERVER))
	{
?>
Example: 
<ul>
	<li><a href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']; ?>?car=HondaInsight&amp;debug=3">Honda Insight (very verbose)</a></li>
	<li><a href="http://<?php echo $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']; ?>?car=FordFusion&amp;debug=1">Ford Fusion (verbose)</a></li>


<?php
	}
    exit;
}

function set_debug_level()
{
    if (defined('DEBUG'))
    {
        return;
    }

    if (isset($_COOKIE['debug']))
    {
        $debug_level = filter_input(INPUT_COOKIE, 'debug', FILTER_SANITIZE_NUMBER_INT);
    }

    if (isset($_GET['debug']))
    {
        $debug_level = filter_var($_GET['debug'], FILTER_SANITIZE_NUMBER_INT);
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
