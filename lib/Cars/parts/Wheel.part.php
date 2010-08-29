<?php

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

