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
