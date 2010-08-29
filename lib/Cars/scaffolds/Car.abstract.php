<?php

class CarException extends Exception
{
    const ERROR_INAPPROPRIATE_GEAR = 'The appropriate gear for this action is not set.';
}

abstract class Car extends CarPartSubject implements Automobile
{
    const STATE_POWERED_OFF = 0;
    const STATE_POWERED_ON = 1;

    const NOTICE_STATE_CHANGED = 'The car\'s state has changed';

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
    protected $state;
    protected $currentGear;

    /**
    * Make each Car class build itself.
    */
    abstract protected function build();

    public function __construct()
    {
        $this->build();
    }

    public static function autoloader($className)
    {
        if (($pos = strpos($className, "Car")) !== false)
        {
            $filename = CARS_LIB_PATH . "/" . substr($className, 0, $pos) . ".car.php";

            require $filename;            
        }
    }

    public function turnOn()
    {
        $this->state = self::STATE_POWERED_ON;
        $this->official_notice = array('notice' => self::NOTICE_STATE_CHANGED,
                                       'value' => $this->state);
        // Functional equivalent of running $this->notify();
        attemptAction(get_class($this), array('turn on the car', 'turned on the car'), array($this, 'notify'), array($this->currentGear - 1));
    }

    public function turnOff()
    {
        $this->state = self::STATE_POWERED_OFF;
        $this->official_notice = array('notice' => self::NOTICE_STATE_CHANGED,
                                       'value' => $this->state);
        // Functional equivalent of running $this->notify();
        attemptAction(get_class($this), array('turn off the car', 'turned off the car'), array($this, 'notify'), array($this->currentGear - 1));
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
            // Functional equivalent of running $this->gasTank->refuel($amount);
            attemptAction(get_class($this), 
                          array('refuel by ' . $amount . ' gallons', 'refueled by ' . $amount . ' gallons'), 
                          array($this->gasTank, 'refuel'), 
                          array($amount));
        }
        catch(GasTankException $e)
        {
            if ($e->getMessage() == GasTankException::NOTICE_TOO_MUCH_GAS)
            {
                printf("Inform the clerk that %.2f gallons needs to be refunded.\n", $e->remainingGas);
            }
            else
            {
                // Something else happened.
                throw $e;
            }
        }
    }

    public function getMileage()
    {
        return (float)$this->drivetrain->getDistanceTravelled();
    }

    public function calculateFuelEfficiency()
    {
        $distance = $this->getMileage();
        $fuelUsed = $this->gasTank->calculateFuelUsedPerTank();

        return $distance / $fuelUsed;
    }

    public function getFuelRemaining()
    {
        return $this->gasTank->getFuelRemaining();
    }

    public function downShift()
    {
        // downShift irrationally increases the gear value.
        // Functional equivalent of running $this->gearShaft->changeGear($this->currentGear + 1);
        attemptAction(get_class($this), 'downshift', array($this->gearShaft, 'changeGear'), array($this->currentGear + 1));
        ++$this->currentGear;

        return $this->currentGear;
    }

    public function upShift()
    {
        // Upshift irrationally decreases the gear value.        
        // Functional equivalent of running $this->gearShaft->changeGear($this->currentGear - 1);
        attemptAction(get_class($this), 'upshift', array($this->gearShaft, 'changeGear'), array($this->currentGear - 1));
        --$this->currentGear;

        return $this->currentGear;
    }

    public static function formatStat($statistic)
    {
        return sprintf('%.1f', round($statistic, 1));
    }
}
