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

define('ENVIRONMENT', 'dev');
define('DEBUG', 1);

abstract class CarPartSubject implements SplSubject
{
    protected $observers = null;

    // This is the message we wish to relay to the observers.
    public $official_notice;

    /* For observer pattern */
    public function attach(SplObserver $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(SplObserver $observer_in)
    {
        foreach ($this->observers as $key => $observer)
        {
            if (spl_object_hash($observer) == spl_object_hash($observer_in))
            {
                unset($this->observers[$key]);

                return true;
            }
        }

        return false;
    }

    public function notify()
    {
        if (!is_array($this->observers) || empty($this->observers))
        {
            return;
        }

        foreach ($this->observers as $observer)
        {
            $observer->update($this);
        }
    }
}

interface Engine
{
    public function __construct(GasTank $gasTank);
    public function revUp($footPressure);
    public function revDown($footPressure);   
}

class CombustionEngine implements Engine, SplSubject
{
    const PRESSURE_FORCE_RATIO = 0.2;
    private $gasTank;

    /* Engines are tightly coupled **in principle** with gas tanks.  Neither is any good at anything without the other.
       That is why we are tightly coupling them here in the logic.
    */
    public function __construct(GasTank $gasTank)
    {
        $this->gasTank = $gasTank;
    }

    public function revUp($footPressure)
    {

    }

    public function revDown($footPressure)
    {
    }

    /* For observer pattern */
    public function attach(SplObserver $observer)
    {
    }

    public function detach(SplObserver $observer)
    {
    }

    public function notify()
    {
    }
}

class HybridEngine extends CombustionEngine implements Engine
{

}

class DriveTrainException extends Exception
{
    const ERROR_INVALID_WHEEL_NUMBER = 'Invalid wheel number.';
    const ERROR_INVALID_WHEEL_OBJECT = 'Invalid wheel object.';
    const ERROR_NOT_IMPLEMENTED_YET = 'Feature has not been implemented yet';
}

abstract class DriveTrain implements SplObserver
{
    protected $wheels;
    protected $currentGear = GearShaft::GEAR_PARK;

    public function __construct(array $wheels)
    {
        foreach ($wheels as $wheel)
        {
            if (!($wheel instanceof Wheel))
            {
                throw new DriveTrainException(DriveTrainException::ERROR_INVALID_WHEEL_OBJECT);
            }

            $this->wheels[] = $wheel;
        }
    }

    private function convertAngleToDirection($steeringWheelAngle)
    {
        if ($steeringWheelAngle != 0.0)
        {
            throw new DriveTrainException(self::ERROR_NOT_IMPLEMENTED_YET);
        }

        // Since all we can do is drive straight, we simply set them to all 0.0 angles.
        return array(0, 0, 0, 0);
    }

    public function turn($steeringWheelAngle)
    {
        // First, figure out how the wheels should be turned.
        $directions = $this->convertAngleToDirection($steeringWheelAngle);

        // Next, set the wheels' direction.  
        foreach ($this->wheels as $i => $wheel)
        {
            $wheel->turn($directions[$i]);
        }
    }

    /* For observer pattern */
    // This gets called whenever the engine's state changes.
    public function update(SplSubject $subject)
    {
        // Listen for gear change notices.
        if ($subject instanceof GearShaft)
        {
            if ($subject->official_notice['notice'] == GearShaft::NOTICE_GEAR_CHANGED)
            {
                if (DEBUG >= 1)
                {
                    printf(__CLASS__ . ": Notified that gear has changed from %s to %s\n", $this->currentGear, $subject->official_notice['value']);
                }

                $this->currentGear = $subject->official_notice['value'];
            }
        }
    }
}

class CarDriveTrain extends DriveTrain
{
    const NUMBER_OF_WHEELS = 4;

    public function __construct(array $wheels)
    {
        // Do some sanity checks.
        if (count($wheels) != self::NUMBER_OF_WHEELS)
        {
            throw DriveTrainException(DriveTrainException::ERROR_INVALID_WHEEL_NUMBER);
        }

        parent::__construct($wheels);
    }
}

class Wheel
{
    const FORCE_SPEED_RATIO = 0.08;
    const SPEED_MILE_RATIO = 0.005;

    private $speed;
    private $distance = 0;
    private $direction;

    public function turn($direction)
    {
        $this->direction = $direction;
    }

    public function spinForward($forceApplied)
    {
        // Set the traveling speed.
        $this->speed = self::FORCE_SPEED_RATIO * $forceApplied;
        $this->distance += SPEED_MILE_RATIO * $this->speed;
    }

    public function spinBackward($forceApplied)
    {
        // Set the traveling speed (negative for reverse).
        $this->speed = self::FORCE_SPEED_RATIO * $forceApplied;
        $this->distance -= SPEED_MILE_RATIO * $this->speed;
    }

    public function getSpeed()
    {
        return $this->speed;
    }

    public function getDistanceTraveled()
    {
        return $this->distance;
    }
}

class GasTankException extends Exception
{
    const ERROR_OUT_OF_GAS = 'Not enough fuel remaining.';
    const NOTICE_TOO_MUCH_GAS = 'Attempted to insert too much gasoline.';

    public $remainingGas;
}

class GasTank
{
    const REFUEL_UNTIL_FULL = -9999.5;

    private $tankSize;
    private $fuel;

    public function __construct($tankSize)
    {
        $this->tankSize = $tankSize;
    }

    public function refuel($amount)
    {
        // Sanity checks.
        if (!is_numeric($amount))
        {
            error_log('Invalid refuel amount: ' . $amount);
            trigger_error('Invalid refuel amount.', E_USER_ERROR);
        }

        if ($amount === self::REFUEL_UNTIL_FULL)
        {
            $this->fuel = $this->tankSize;
        }
        else
        {
            if ($this->fuel + $amount > $this->fuel)
            {
                $e = new GasTankException(GasTankException::NOTICE_TOO_MUCH_GAS);
                $e->remainingGas = ($this->fuel + $amount) - $this->tankSize;
                $this->fuel = $this->tankSize;

                throw $e;
            }

            $this->fuel += $amount;
        }
    }

    public function getFuelRemaining()
    {
        return $this->fuel;
    }

    /**
    * Releases fuel to the engine for consumption.
    */
    public function releaseFuel($amount)
    {
        if ($amount > $this->fuel)
        {
            throw new GasTankException(GasTankException::ERROR_OUT_OF_GAS);
        }

        $this->fuel -= $amount;
    }
}

class GearShaftException extends Exception
{
    const ERROR_MUST_PARK_REVERSE = 'Must park to go into reverse.';
    const ERROR_MUST_PARK_DRIVE = 'Must park to go forward.';
}

class GearShaft extends CarPartSubject
{
    const NOTICE_GEAR_CHANGED = 'Gear changed';

    const GEAR_PARK = 0;
    const GEAR_REVERSE = 1;
    const GEAR_NEUTRAL = 2;
    const GEAR_DRIVE = 3;

    private $currentGear = self::GEAR_PARK;

    public function changeGear($gear)
    {
        if ($gear == self::GEAR_REVERSE && $this->currentGear == self::GEAR_DRIVE)
        {
            throw new GearShaftException(GearShaftException::ERROR_MUST_PARK_REVERSE);
        }
        else if ($gear == self::GEAR_DRIVE && $this->currentGear == self::GEAR_REVERSE)
        {
            throw new GearShaftException(GearShaftException::ERROR_MUST_PARK_DRIVE);
        }

        $this->currentGear = $gear;
        $this->official_notice = array('notice' => self::NOTICE_GEAR_CHANGED,
                                       'value' => $gear);
        $this->notify();
    }
}

interface Automobile
{
    protected function build();
    public function drive($footPressure, $minutesToDrive, $steeringWheelAngle);
    public function brake($footPressure);
    public function refuel();
    public function getMileage();
    public function calculateFuelEfficiency();
    public function getRemainingFuel();
    public function downShift();
    public function upShift();
}

class CarException extends Exception
{
    const ERROR_INAPPROPRIATE_GEAR = 'The appropriate gear for this action is not set.';
}

abstract class Car implements Automobile
{
    // Objects for the Composite Pattern.
    /**
    * @var Engine
    */
    protected $engine;
    /**
    * @var CarDriveTrain
    */
    protected $drivetrain;
    /**
    * @var GasTank
    */
    protected $gasTank;
    /**
    * @var GearShift
    */
    protected $gearShaft;

    // Class properties.
    protected $currentGear;

    protected function build()
    {
        trigger_error('If this function is not overrideen, your app is not coded properly.', E_USER_ERROR);
    }

    public function __construct()
    {
        $this->build();
    }

    // Right now our car will only be able to drive in a straight line
    // either forward or in reverse.
    public function drive($footPressure, $minutesToDrive, $steeringWheelAngle)
    {
        // Sanity checks.
        if ($this->currentGear != GearShaft::GEAR_DRIVE || $this->currentGear != GearShaft::GEAR_REVERSE)
        {
            throw new CarException(CarException::ERROR_INAPPROPRIATE_GEAR);
        }

        if ($this->gasTank->getFuelRemaining() != 0)
        {
            throw new GasTankException(GasTankException::ERROR_OUT_OF_GAS);
        }

        $this->drivetrain->turn($steeringWheelAngle);

        // Use a loop; one minute == one iteration.
        for ($a = 0; $a < $minutesToDrive; ++$a)
        {
            $this->engine->revUp($footPressure);
        }
    }

    public function brake($footPressure)
    {
        $this->engine->revDown($footPressure);
    }

    public function refuel($amount = GasTank::REFUEL_UNTIL_FULL)
    {
        try
        {
            $this->gasTank->refuel($amount);
        }
        catch(GasTankException $e)
        {
            if ($e->getMessage() == GasTankException::NOTICE_TOO_MUCH_GAS)
            {
                printf("Inform the clerk that %d gallons needs to be refunded.\n", $e->remainingGas);
            }
            else
            {
                // Something else happened.
                throw $e;
            }
        }
    }

    public function getMileage() { }
    public function calculateFuelEfficiency() { }

    public function getRemainingFuel() { }
    public function downShift() { }
    public function upShift() { }
}

/**
* Hybrid cars are virtually identical to combustion engine cars.
* For this demo, we won't bother differentiating the two.
*/
class HondaInsightCar extends Car implements Automobile
{
    protected function build()
    {
        // Add a gas tank.
        $this->gasTank = new GasTank(10.0);

        // Add an engine.
        $this->engine = new Engine($this->gasTank);

        // Add a GearShaft.
        $this->gearShaft = new GearShaft;

        // Add four Wheels to the drive train using a for loop.
        $wheels = array();
        for ($a = 0; $a < 4; ++$a)
        {
            $wheels[] = new Wheel;
        }

        // Add a drive train
        $this->drivetrain = new CarDriveTrain($wheels);

        // Register CarDriveTrain as an observer of GearShaft.
        $this->gearShaft->attach($this->drivetrain);
    }
}

$car = new HondaInsightCar;
$car->refuel();
// 0 degrees == straight ahead.
$car->drive(1.0, 5.2, 0.0);
echo "Miles driven: " . $car->calculateMileage() . "\n";
echo "Current mileage: " . $car->calculateFuelEfficiency() . "\n";
