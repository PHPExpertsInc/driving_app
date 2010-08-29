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
    const NOTICE_REVS = 'Engine is running';
    const NOTICE_REVS_INCREASED = 'Engine revs increased';
    const NOTICE_REVS_DECREASED = 'Engine revs decreased';

    private $gasTank;
    private $currentForce = 0;

    protected $engineStats;

    /* Engines are tightly coupled **in principle** with gas tanks.  Neither is any good at anything without the other.
       That is why we are tightly coupling them here in the logic.
    */
    public function __construct(EngineStats $engineStats, GasTank $gasTank)
    {
        $this->engineStats = $engineStats;
        $this->gasTank = $gasTank;
    }

    private function injectFuel()
    {
        $gasNeeded = $this->currentForce * $this->engineStats->FORCE_FUEL_RATIO;
        $this->gasTank->releaseFuel($gasNeeded);
    }

    public function rev()
    {
        $this->injectFuel();
        $this->ro_official_notice = array('notice' => self::NOTICE_REVS,
                                          'value'  => $this->currentForce);
        $this->notify();
    }

    public function revUp($footPressure)
    {
        $this->currentForce = $footPressure * $this->engineStats->PRESSURE_FORCE_RATIO;
        $this->injectFuel();
        $this->ro_official_notice = array('notice' => self::NOTICE_REVS_INCREASED,
                                          'value'  => $this->currentForce);
        $this->notify();        
    }

    public function revDown($footPressure)
    {
        $this->currentForce = $footPressure * $this->engineStats->PRESSURE_FORCE_RATIO;
        $this->injectFuel();
        $this->ro_official_notice = array('notice' => self::NOTICE_REVS_DECREASED,
                                          'value'  => $this->currentForce);
        $this->notify();        
    }
}
