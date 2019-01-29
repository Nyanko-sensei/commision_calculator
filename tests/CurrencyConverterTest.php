<?php

declare(strict_types=1);

use CommissionCalculator\Services\CurrencyConverter;
use CommissionCalculator\Services\CurrencyRepository;
use PHPUnit\Framework\TestCase;

final class  CurrencyConverterTest extends TestCase
{
    /**
     * @test
     */
    public function currencyCanBeChanegedToAndFromEUR(): void
    {
        $currencyConverter = new CurrencyConverter(new CurrencyRepository());

        $this->assertEquals(1000, $currencyConverter->convertToEUR(1149.7, 'USD'));
        $this->assertEquals(1149.7, $currencyConverter->convertFromEUR(1000, 'USD'));

        $this->assertEquals(1000, $currencyConverter->convertToEUR(129530, 'JPY'));
        $this->assertEquals(129530, $currencyConverter->convertFromEUR(1000, 'JPY'));
    }
}