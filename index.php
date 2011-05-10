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

require 'lib/Cars/Cars.php';

Cars::init();

// Use the abstract factory pattern.
$car = CarFactory::loadCar($_GET['car']);
$car->downShift();
$car->turnOn();      // Expect "BZZZ: Must be in park to turn on the car."
                     //        "HondaInsightCar: Unsuccessfully turned on the car."

// Get into park.
// Expect 2x "HondaInsightCar: Successfully upshifted."
while ($car->upShift() != GearShaft::GEAR_PARK);

$car->refuel();      // Expect "HondaInsightCar: Successfully refueled by max gallons."
echo "Fuel remaining: " . Car::formatStat($car->getFuelRemaining()) . " gallons.\n";   // Expect "Fuel remaining: 10.0 gallons."

// Get into drive.
// Expect 3x "HondaInsightCar: Successfully downshifted."
while ($car->downShift() != GearShaft::GEAR_DRIVE);

// Accelerate to 60 mph.
$seconds = 0;
while ($car->getSpeed() < 60)
{
    $car->accelerate(1.0, 1);
    ++$seconds;
}
printf("Accelerated to %s mph in %d seconds.\n",
       Car::formatStat($car->getSpeed()),
       $seconds);

// Drive for 60 minutes.
$car->drive(60, 0.0);

// Expect "Miles driven: 0.0 miles."
echo "Miles driven: " . Car::formatStat($car->getMileage()) . " miles.\n";
echo "Fuel remaining: " . Car::formatStat($car->getFuelRemaining()) . " gallons.\n";   // Expect "Fuel remaining: 10.0 gallons."

// Expect "Current mileage: 0.0 mpg."
echo "Current mileage: " . Car::formatStat($car->calculateFuelEfficiency()) . " mpg.\n";

// Get into park.
// Expect 3x "HondaInsightCar: Successfully upshifted."
while ($car->upShift() != GearShaft::GEAR_PARK);
$car->turnOff();      // Expect "HondaInsightCar: Successfully turned off the car."

