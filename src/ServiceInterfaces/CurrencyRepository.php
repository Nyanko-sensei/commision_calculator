<?php

namespace CommissionCalculator\ServiceInterfaces;

use CommissionCalculator\Models\Currency;

interface CurrencyRepository
{
    public function getCurrencyByCode(string $code): Currency;
}