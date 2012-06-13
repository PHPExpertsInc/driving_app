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

interface Automobile
{
	public function __construct(Engine $engine, GasTank $gasTank, GearShaft $gearShaft, DriveTrain $driveTrain);

    public function turnOn();
    public function turnOff();
    public function accelerate($footPressure, $secondsToAccelerate);
    public function drive($minutesToDrive, $steeringWheelAngle);
    public function brake($footPressure);
    public function refuel();
    public function getMileage();
    public function calculateFuelEfficiency();
    public function getFuelRemaining();
    public function downShift();
    public function upShift();
}

class AutomobileStats
{
	public $gasTankSize;
	public $numberOfWheels;

	/** @var EngineStats **/
	public $engineStats;
}
