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

abstract class CarPartSubject implements SplSubject
{
    protected $observers = null;

    // This is the message we wish to relay to the observers.
    public $official_notice;

    /* For observer pattern */
    public function attach(SplObserver $observer)
    {
        $this->observers[] = $observer;
    }

    public function detach(SplObserver $observer_in)
    {
        foreach ($this->observers as $key => $observer)
        {
            if (spl_object_hash($observer) == spl_object_hash($observer_in))
            {
                unset($this->observers[$key]);

                return true;
            }
        }

        return false;
    }

    public function notify()
    {
        if (!is_array($this->observers) || empty($this->observers))
        {
            return;
        }

        foreach ($this->observers as $observer)
        {
            $observer->update($this);
        }
    }
}
