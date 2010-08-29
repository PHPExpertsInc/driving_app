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

class GasTankException extends Exception
{
    const ERROR_OUT_OF_GAS = 'Not enough fuel remaining.';
    const NOTICE_TOO_MUCH_GAS = 'Attempted to insert too much gasoline.';

    public $remainingGas;
}

class GasTank
{
    const REFUEL_UNTIL_FULL = 'max';

    private $tankSize;
    private $fuel = 0.0;

    public function __construct($tankSize)
    {
        $this->tankSize = $tankSize;
    }

    public function refuel($amount)
    {
        // Sanity checks.
        if ($amount != self::REFUEL_UNTIL_FULL && !is_numeric($amount))
        {
            error_log('Invalid refuel amount: ' . $amount);
            trigger_error('Invalid refuel amount.', E_USER_ERROR);
        }

        if ($amount === self::REFUEL_UNTIL_FULL)
        {
            $this->fuel = $this->tankSize;
        }
        else
        {
            if ($this->fuel + $amount > $this->tankSize)
            {
                $e = new GasTankException(GasTankException::NOTICE_TOO_MUCH_GAS);
                $e->remainingGas = ($this->fuel + $amount) - $this->tankSize;
                $this->fuel = $this->tankSize;

                throw $e;
            }

            $this->fuel += $amount;
        }
    }

    public function getFuelRemaining()
    {
        return (float)$this->fuel;
    }

    public function calculateFuelUsedPerTank()
    {
        return (float)$this->tankSize - (float)$this->fuel;
    }

    /**
    * Releases fuel to the engine for consumption.
    */
    public function releaseFuel($amount)
    {
        if ($amount > $this->fuel)
        {
            throw new GasTankException(GasTankException::ERROR_OUT_OF_GAS);
        }

        $this->fuel -= $amount;
    }
}

