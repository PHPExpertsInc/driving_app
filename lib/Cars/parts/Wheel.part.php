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
    const FORCE_SPEED_RATIO = 7.18;

    private $speed = 0;
    private $distance = 0;
    private $direction;

    public function turn($direction)
    {
        $this->direction = $direction;
    }
    
    public function changeSpinSpeed($forceApplied)
    {
        // Set the traveling speed.
        $acceleration = self::FORCE_SPEED_RATIO * $forceApplied;
        
        if ($this->speed == 0)
        {
            $this->speed = $acceleration;
        }
        else
        {
            $this->speed = (float)$this->speed * (float)$acceleration;
        }
    }

    public function spinForward($forceApplied, $timeDilation = null)
    {
        if (is_null($timeDilation))
        {
            // Set the default time Dilation to one second.
            $timeDilation = 1/60;
        }

        //echo "Acceleration: $acceleration | Speed2: {$this->speed}\n";
        $this->distance += $timeDilation * $this->speed;

        if (DEBUG >= 4)
        {
            echo "Dilation: $timeDilation | Speed: " . $this->speed . " | Distance: {$this->distance}\n";
        }
    }

    public function spinBackward($forceApplied, $timeDilation = null)
    {
        if (is_null($timeDilation))
        {
            // Set the default time Dilation to one second.
            $timeDilation = 1/60;
        }

        // Set the traveling speed (negative for reverse).
        $this->speed = self::FORCE_SPEED_RATIO * $forceApplied;
        $this->distance -= $timeDilation * $this->speed;
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

