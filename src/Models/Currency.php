<?php
namespace CommissionCalculator\Models;

use Carbon\Carbon;

class Currency
{
    /** @var  string */
    private $code;

    /** @var float */
    private $rate;

    /** @var  int */
    private $decimalPlaces;

    public static function createWithData($code, $rate, $decimalPlaces) {
        $currency = new self();
        $currency->setCode($code);
        $currency->setRate($rate);
        $currency->setDecimalPlaces($decimalPlaces);

        return $currency;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return int
     */
    public function getDecimalPlaces(): int
    {
        return $this->decimalPlaces;
    }

    /**
     * @param int $decimalPlaces
     */
    public function setDecimalPlaces(int $decimalPlaces)
    {
        $this->decimalPlaces = $decimalPlaces;
    }
}