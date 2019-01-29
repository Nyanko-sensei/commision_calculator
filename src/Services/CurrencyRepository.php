<?php

namespace CommissionCalculator\Services;

use CommissionCalculator\Models\Currency;
use CommissionCalculator\ServiceInterfaces\CurrencyRepository as CurrencyRepositoryInterface;

class CurrencyRepository implements CurrencyRepositoryInterface
{
    /** @var  Currency[] */
    private $currencies;

    public function __construct()
    {
        $this->currencies = [
            'EUR' => Currency::createWithData('EUR', 1, 2),
            'USD' => Currency::createWithData('USD', 1.1497, 2),
            'JPY' => Currency::createWithData('JPY', 129.53, 0),
        ];
    }

    public function getCurrencyByCode(string $code): Currency
    {
        return $this->currencies[$code] ?? $this->currencies['EUR'];
    }
}