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

class GearShaftException extends Exception
{
    const ERROR_CAR_IS_OFF = 'Car must be turned on to change gears.';
    const ERROR_MUST_PARK_ON = 'Must be in park to turn on the car.';
    const ERROR_MUST_PARK_REVERSE = 'Must be in park to go into reverse.';
    const ERROR_MUST_PARK_DRIVE = 'Must in park to go forward.';

    const NOTICE_MIN_GEAR = 'Cannot shift higher';
    const NOTICE_MAX_GEAR = 'Cannot shift lower.';
}

class GearShaft extends CarPartSubject implements SplObserver
{
    const STATUS_GEAR_CHANGED = 'Gear changed';

    const GEAR_PARK = 0;
    const GEAR_REVERSE = 1;
    const GEAR_NEUTRAL = 2;
    const GEAR_DRIVE = 3;

    private $currentCarState = Car::STATE_POWERED_OFF;
    private $currentGear = self::GEAR_PARK;

    public function changeGear($gear)
    {
        // Do nothing if gear is the same.
        if ($gear == $this->currentGear) { return $gear; }

        // Figure out if we're upshifting or downshifting.
        $downShifting = (bool)($gear > $this->currentGear);

        // Sanity checks.
        // Can't downshift with the car off.
        if ($downShifting === true &&   $this->currentCarState == Car::STATE_POWERED_OFF)
        {
            throw new GearShaftException(GearShaftException::ERROR_CAR_IS_OFF);
        }

        if ($gear < self::GEAR_PARK)
        {
            throw new GearShaftException(GearShaftException::NOTICE_MIN_GEAR);
        }
        else if ($gear > self::GEAR_DRIVE)
        {
            throw new GearShaftException(GearShaftException::NOTICE_MAX_GEAR);
        }

        if ($gear == self::GEAR_REVERSE && $this->currentGear == self::GEAR_DRIVE)
        {
            throw new GearShaftException(GearShaftException::ERROR_MUST_PARK_REVERSE);
        }
        else if ($gear == self::GEAR_DRIVE && $this->currentGear == self::GEAR_REVERSE)
        {
            throw new GearShaftException(GearShaftException::ERROR_MUST_PARK_DRIVE);
        }

        $this->currentGear = $gear;
        $this->official_notice = array('notice' => self::STATUS_GEAR_CHANGED,
                                       'value' => $gear);
        $this->notify();

        return $this->currentGear;
    }

    /* For observer pattern */
    // This gets called whenever the engine's state changes.
    public function update(SplSubject $subject)
    {
        // Listen for Car state change notices.
        if ($subject instanceof Car)
        {
            if ($subject->official_notice['notice'] == Car::NOTICE_STATE_CHANGED)
            {
                if (DEBUG >= 3)
                {
                    printf(__CLASS__ . ": Notified that car's state has changed to %s.\n", $subject->official_notice['value']);
                }

                // Turn off if the car is not in park when trying to start.
                if ($subject->official_notice['value'] == Car::STATE_POWERED_ON && $this->currentGear != self::GEAR_PARK)
                {
                    throw new GearShaftException(GearShaftException::ERROR_MUST_PARK_ON);
                }

                $this->currentCarState = $subject->official_notice['value'];
            }
        }
    }
}

