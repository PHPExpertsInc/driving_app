<?php

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
        $this->engine = new HybridEngine($this->gasTank);

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

        // Register GearShaft as an observer of Car.
        $this->attach($this->gearShaft);

        // Register CarDriveTrain as an observer of GearShaft.
        $this->gearShaft->attach($this->drivetrain);        
    }
}
