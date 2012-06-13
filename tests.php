<?php
/**
* Driving App
*   Copyright © 2010, 2011 Theodore R. Smith <theodore@phpexperts.pro>
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

// Attempt to change gears when off.
$car->downShift();   // Expect "BZZZ: Car must be on to change gears."
                     //        "HondaInsightCar: Unsuccessfully downshifted."
$car->turnOn();      // Expect "HondaInsightCar: Successfully turned on the car."
$car->downShift();   // Expect "HondaInsightCar: Successfully downshifted."
$car->turnOff();     // Expect "HondaInsightCar: Successfully turned off the car."

$car->turnOn();      // Expect "BZZZ: Must be in park to turn on the car."
                     //        "HondaInsightCar: Unsuccessfully turned on the car."

// Get into park.
// Expect 2x "HondaInsightCar: Successfully upshifted."
while ($car->upShift() != GearShaft::GEAR_PARK);

$car->turnOn();      // Expect "HondaInsightCar: Successfully turned on the car."
$car->turnOff();     // Expect "HondaInsightCar: Successfully turned off the car."

echo "Fuel remaining: " . Car::formatStat($car->getFuelRemaining()) . " gallons.\n";   // Expect "Fuel remaining: 0.0 gallons."
$car->refuel(1.1);   // Expect "HondaInsightCar: Successfully refueled by 1.1 gallons."
echo "Fuel remaining: " . Car::formatStat($car->getFuelRemaining()) . " gallons.\n";   // Expect "Fuel remaining: 1.1 gallons."
$car->refuel();      // Expect "HondaInsightCar: Successfully refueled by max gallons."
echo "Fuel remaining: " . Car::formatStat($car->getFuelRemaining()) . " gallons.\n";   // Expect "Fuel remaining: 10.0 gallons."
$car->refuel(0.5);   // Expect "Inform the clerk that 0.50 gallons needs to be refunded."

// 0 degrees == straight ahead.
// Expect "BZZZ: The appropriate gear for this action is not set; the car cannot move."
//        "HondaInsightCar: Unsuccessfully ensured a valid gear is set."
$car->drive(5.2, 0.0);

// Expect "Miles driven: 0.0 miles."
echo "Miles driven: " . Car::formatStat($car->getMileage()) . " miles.\n";

// Expect "Current mileage: 0.0 mpg."
echo "Current mileage: " . Car::formatStat($car->calculateFuelEfficiency()) . " mpg.\n";

$car->turnOn();      // Expect "HondaInsightCar: Successfully turned on the car."

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

//echo "Current speed: " . Car::formatStat($car->getSpeed()) . " mph.\n";
echo "Miles driven: " . Car::formatStat($car->getMileage()) . " miles.\n";

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

echo "Refueling...\n";
$car->refuel();

$car->turnOn();

// Get into drive.
// Expect 3x "HondaInsightCar: Successfully downshifted."
while ($car->downShift() != GearShaft::GEAR_DRIVE);

echo "Accelerating to 60 mph...\n";
// Accelerate to 60 mph.
$seconds = 0;
while ($car->getSpeed() < 60)
{
    $car->accelerate(1.0, 1);
    ++$seconds;
}

echo "Driving until the car runs out of fuel...\n";
try
{
    while (true)
    {
        $car->drive(1, 0);
    }
}
catch (GasTankException $e)
{
    if ($e->getMessage() != GasTankException::ERROR_OUT_OF_GAS)
    {
        // Unexpected error
        throw $e;
    }
}

printf("Max distance on a full tank: %s miles.\n", 
       Car::formatStat($car->getMileage()));
printf("Fuel efficiency: %s mpg.\n", 
       Car::formatStat($car->calculateFuelEfficiency()));