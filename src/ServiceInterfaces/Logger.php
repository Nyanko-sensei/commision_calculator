<?php
namespace CommissionCalculator\ServiceInterfaces;

interface Logger
{
    public function log(string $msg):void;
}