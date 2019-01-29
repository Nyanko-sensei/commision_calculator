<?php

namespace CommissionCalculator\Services;

use CommissionCalculator\ServiceInterfaces\CurrencyConverter as CurrencyConverterInterface;
use CommissionCalculator\ServiceInterfaces\CurrencyRepository;

class CurrencyConverter implements CurrencyConverterInterface
{
    /** @var  CurrencyRepository */
    private $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    public function convertFromEUR(float $amount, string $currencyCode): float
    {
        $currency = $this->currencyRepository->getCurrencyByCode($currencyCode);

        return $amount * $currency->getRate();
    }

    public function convertToEUR(float $amount, string $currencyCode): float
    {
        $currency = $this->currencyRepository->getCurrencyByCode($currencyCode);

        return $amount / $currency->getRate();
    }
}