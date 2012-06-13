<?php
/**
* Driving App
*   Copyright © 2012 Theodore R. Smith <theodore@phpexperts.pro>
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

class CarStatsManager
{
	/** @return AutomobileStats[] */
	public static $carStats;

	// TODO: Get this to pull data from a data layer.
	/** @return AutomobileStats **/
	public function getStats($carName)
	{
		if (static::$carStats[$carName] !== null) return static::$carStats[$carName];

		switch ($carName)
		{
			case 'FordFusionCar': $stats = array('ffr' => 0.22, 'pfr' => '0.21', 'wheels' => 4, 'tankSize' => 23.0); break;
			case 'HondaInsightCar': $stats = array('ffr' => 0.10, 'pfr' => '0.21', 'wheels' => 4, 'tankSize' => 10.6); break;
		}

		$engineStats = new EngineStats;
		$engineStats->FORCE_FUEL_RATIO = $stats['ffr'];
		$engineStats->PRESSURE_FORCE_RATIO = $stats['pfr'];

		$carStats = new AutomobileStats;
		$carStats->numberOfWheels = $stats['wheels'];
		$carStats->gasTankSize = $stats['tankSize'];
		$carStats->engineStats = $engineStats;

		static::$carStats[$carName] = $carStats;

		return static::$carStats[$carName];
	}
}
