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

class DriveTrainException extends Exception
{
    const ERROR_INVALID_WHEEL_NUMBER = 'Invalid wheel number.';
    const ERROR_INVALID_WHEEL_OBJECT = 'Invalid wheel object.';
    const ERROR_NOT_IMPLEMENTED_YET = 'Feature has not been implemented yet.';
    const ERROR_NO_FORCE_APPLIED = 'No force applied.';
}

abstract class DriveTrain implements SplObserver
{
    protected $wheels;
    protected $currentGear = GearShaft::GEAR_PARK;
    protected $currentEngineForce = 0;

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
    
    private function deliverThrust()
    {
        foreach ($this->wheels as /** @var Wheel **/ $wheel)
        {
            $wheel->changeSpinSpeed($this->currentEngineForce);
            $wheel->spinForward($this->currentEngineForce, 1/2000 /* one iteration per second */);
        }
    }
    
    public function spinWheels()
    {
        // Sanity checks.
        if (!is_numeric($this->currentEngineForce))
        {
            trigger_error('Invalid currentEngineForce', E_USER_ERROR);
        }

        if ($this->currentEngineForce == 0)
        {
            // FIXME: Temp hack.
            //throw new DriveTrainException(DriveTrainException::ERROR_NO_FORCE_APPLIED);
        }

        foreach ($this->wheels as /** @var Wheel **/ $wheel)
        {
            if ($this->currentGear == GearShaft::GEAR_DRIVE)
            {
                $wheel->spinForward($this->currentEngineForce);
            }
            else if ($this->currentGear == GearShaft::GEAR_REVERSE)
            {
                $wheel->spinBackward($this->currentEngineForce);
            }
        }
    }

    private function convertAngleToDirection($steeringWheelAngle)
    {
        if ($steeringWheelAngle != 0.0)
        {
            throw new DriveTrainException(DriveTrainException::ERROR_NOT_IMPLEMENTED_YET);
        }

        // Since all we can do is drive straight, we simply set them to all 0.0 angles.
        return array(0, 0, 0, 0);
    }

    public function turn($steeringWheelAngle)
    {
        // First, figure out how the wheels should be turned.
        $directions = $this->convertAngleToDirection($steeringWheelAngle);

        // Next, set the wheels' direction.  
        foreach ($this->wheels as $i => /** @var Wheel **/ $wheel)
        {
            $wheel->turn($directions[$i]);
        }
    }

    public function getDistanceTravelled()
    {
        $distance = 0.0;
        foreach ($this->wheels as /** @var Wheel **/ $wheel)
        {
            $distance += $wheel->getDistanceTravelled();
        }

        // Return average speed for all.
        return $distance / count($this->wheels);        
    }

    public function getSpeed()
    {
        $speed = 0.0;
        foreach ($this->wheels as /** @var Wheel **/ $wheel)
        {
            $speed += $wheel->getSpeed();
        }

        // Return average speed for all.
        return $speed / count($this->wheels);
    }
    
    /* For observer pattern */
    // This gets called whenever the engine's state changes.
    public function update(SplSubject $subject)
    {
        // Listen for gear change notices.
        if ($subject instanceof GearShaft)
        {
            if ($subject->official_notice['notice'] == GearShaft::STATUS_GEAR_CHANGED)
            {
                if (DEBUG >= 3)
                {
                    printf(__CLASS__ . ": Notified that gear has changed from %s to %s.\n", $this->currentGear, $subject->official_notice['value']);
                }

                $this->currentGear = $subject->official_notice['value'];
            }
        }
        else if ($subject instanceof CombustionEngine)
        {
            if ($subject->official_notice['notice'] == CombustionEngine::STATUS_ENGINE_REVS)
            {
                $this->currentEngineForce = $subject->official_notice['value'];
                //$this->spinWheels();
                $this->deliverThrust();
            }
        }
    }
}
