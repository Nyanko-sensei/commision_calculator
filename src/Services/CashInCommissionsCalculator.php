<?php

namespace CommissionCalculator\Services;

use CommissionCalculator\Models\Transaction;
use CommissionCalculator\ServiceInterfaces\CashInCommissionsCalculator as CashInCommissionsCalculatorInterface;

class CashInCommissionsCalculator implements CashInCommissionsCalculatorInterface
{
    const CASH_IN_COMMISSION_RATE = 0.0003;
    const CASH_IN_COMMISSION_MAX = 5;
    /**
     * @var CurrencyConverter
     */
    private $currencyConverter;

    public function __construct(CurrencyConverter $currencyConverter)
    {
        $this->currencyConverter = $currencyConverter;
    }

    public function calculateCommissionsForTransaction(Transaction $transaction): float
    {
        $commissions = $transaction->getAmount() * self::CASH_IN_COMMISSION_RATE;

        if ($this->IsAboveMaxCashInCommissions($transaction->getCurrencyCode(), $commissions)) {

            $commissions = $this->currencyConverter->convertFromEUR(self::CASH_IN_COMMISSION_MAX,
                $transaction->getCurrencyCode());
        }

        return $commissions;
    }

    /**
     * @param string $currencyCode
     * @param        $commissions
     *
     * @return bool
     */
    private function IsAboveMaxCashInCommissions(string $currencyCode, $commissions): bool
    {
        return $this->currencyConverter->convertToEUR($commissions, $currencyCode) > self::CASH_IN_COMMISSION_MAX;
    }
}