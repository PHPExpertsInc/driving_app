<?php
/**
* Driving App
*   Copyright © 2010, 2012 Theodore R. Smith <theodore@phpexperts.pro>
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

// Abstract Factory Pattern
class CarFactory
{
    private function __construct() { }

    private static function findCars()
    {
        static $cars = null;

        // Cache the cars.
        if (is_null($cars))
        {
            // Find all the files for these class types.
            $cars = array();
            $type = 'car';
            $directory = CARS_LIB_PATH . '/cars';

            $files = scandir($directory);
            filter_files_by_type($files, $type);

            foreach ($files as $file)
            {
                if (($pos = strpos($file, ".$type.php")) !== false)
                {
                    $class = substr($file, 0, $pos);
                    $cars[] = $class;
                }
                else
                {
                    echo "Great: $file\n";
                }
            }
        }

        return $cars;
    }


    /**
    * @param string $model
    * @return Car
    */
    public static function loadCar($car)
    {
        echo "Car: $car\n";
        // Sanity checks.
        if (!is_string($car))
        {
            trigger_error('Invalid car variable.', E_USER_ERROR);
        }

        $available_cars = self::findCars();
        $className = $car;

        if (!in_array($className, $available_cars))
        {
            trigger_error('Could not find car object for ' . $className . '.', E_USER_ERROR);
        }

        $className .= 'Car';

        return self::buildCar($className);
    }

	protected static function buildCar($carClassName)
	{
		/** @var $car Car */
		if (!class_exists($carClassName))
		{
			throw new LogicException("Class $carClassName does not exist.");
		}

		// Get car stats.
		$statsMan = new CarStatsManager;
		$carStats = $statsMan->getStats($carClassName);
		$engineStats = $carStats->engineStats;

		// Add a gas tank.
		$gasTank = new GasTank($carStats->gasTankSize);

		// Add an engine.
		$engine = new CombustionEngine($engineStats, $gasTank);

		// Add a GearShaft.
		$gearShaft = new GearShaft;

		// Add four Wheels to the drive train using a for loop.
		$wheels = array();
		for ($a = 0; $a < $carStats->numberOfWheels; ++$a)
		{
			$wheels[] = new Wheel;
		}

		// Add a drive train
		$drivetrain = new CarDriveTrain($wheels);

		// Register CarDriveTrain as an observer of GearShaft.
		$gearShaft->attach($drivetrain);

		// Register CarDriveTrain as an observer of Engine.
		$engine->attach($drivetrain);

		$car = new $carClassName($engine, $gasTank, $gearShaft, $drivetrain);

		// Register GearShaft as an observer of Car.
		$car->attach($gearShaft);

		return $car;
	}
}

