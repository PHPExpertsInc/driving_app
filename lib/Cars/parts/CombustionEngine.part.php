<?php

class CombustionEngine implements Engine, SplSubject
{
    const PRESSURE_FORCE_RATIO = 0.2;
    private $gasTank;

    /* Engines are tightly coupled **in principle** with gas tanks.  Neither is any good at anything without the other.
       That is why we are tightly coupling them here in the logic.
    */
    public function __construct(GasTank $gasTank)
    {
        $this->gasTank = $gasTank;
    }

    public function revUp($footPressure)
    {

    }

    public function revDown($footPressure)
    {
    }

    /* For observer pattern */
    public function attach(SplObserver $observer)
    {
    }

    public function detach(SplObserver $observer)
    {
    }

    public function notify()
    {
    }
}
