<?php

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

    public function getDistanceTravelled()
    {
        $distance = 0.0;
        foreach ($this->wheels as $wheel)
        {
            $distance += $wheel->getDistanceTravelled();
        }

        // Return average speed for all.
        return $distance / count($this->wheels);        
    }

    public function getSpeed()
    {
        $speed = 0.0;
        foreach ($this->wheels as $wheel)
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
    }
}
