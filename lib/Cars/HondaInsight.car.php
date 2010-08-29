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
