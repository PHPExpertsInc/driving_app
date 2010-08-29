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

class CombustionEngine extends CarPartSubject implements Engine
{
    const PRESSURE_FORCE_RATIO = 0.21;
    const FORCE_FUEL_RATIO = 0.08;
    const STATUS_ENGINE_REVS = 'Engine revs changed';

    private $gasTank;
    
    /* Engines are tightly coupled **in principle** with gas tanks.  Neither is any good at anything without the other.
       That is why we are tightly coupling them here in the logic.
    */
    public function __construct(GasTank $gasTank)
    {
        $this->gasTank = $gasTank;
    }

    private function injectFuel($force)
    {
        $gasNeeded = $force * self::FORCE_FUEL_RATIO;
        $this->gasTank->releaseFuel($gasNeeded);
    }

    public function revUp($footPressure)
    {
        $force = $footPressure * self::PRESSURE_FORCE_RATIO;

        $this->ro_official_notice = array('notice' => self::STATUS_ENGINE_REVS,
                                       'value'  => $force);

        $this->notify();
    }

    public function revDown($footPressure)
    {
        $this->revUp($footPressure * -1);
    }
}
