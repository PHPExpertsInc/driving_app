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

    public function getDistanceTravelled()
    {
        return $this->distance;
    }
}

