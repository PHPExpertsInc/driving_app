<?php

interface Automobile
{
    public function turnOn();
    public function turnOff();
    public function drive($footPressure, $minutesToDrive, $steeringWheelAngle);
    public function brake($footPressure);
    public function refuel();
    public function getMileage();
    public function calculateFuelEfficiency();
    public function getFuelRemaining();
    public function downShift();
    public function upShift();
}
