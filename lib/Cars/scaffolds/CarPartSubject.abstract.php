<?php

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
