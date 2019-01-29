<?php

namespace CommissionCalculator\ServiceInterfaces;

interface CurrencyConverter
{
    public function convertFromEUR(float $amount, string $currencyCode): float;

    public function convertToEUR(float $amount, string $currencyCode): float;
}