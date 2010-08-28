<?php

interface Engine
{
    public function __construct(GasTank $gasTank);
    public function revUp($footPressure);
    public function revDown($footPressure);   
}
