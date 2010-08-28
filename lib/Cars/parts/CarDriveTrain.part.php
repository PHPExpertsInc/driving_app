<?php

class CarDriveTrain extends DriveTrain
{
    const NUMBER_OF_WHEELS = 4;

    public function __construct(array $wheels)
    {
        // Do some sanity checks.
        if (count($wheels) != self::NUMBER_OF_WHEELS)
        {
            throw DriveTrainException(DriveTrainException::ERROR_INVALID_WHEEL_NUMBER);
        }

        parent::__construct($wheels);
    }
}
