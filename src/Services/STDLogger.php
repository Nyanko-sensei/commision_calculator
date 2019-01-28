<?php
namespace CommissionCalculator\Services;

use CommissionCalculator\ServiceInterfaces\Logger;

class STDLogger implements Logger
{
    public function log(string $msg): void
    {
        fwrite(STDOUT, "$msg\n");
    }
}