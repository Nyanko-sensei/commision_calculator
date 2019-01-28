<?php
namespace CommissionCalculator\Services;

use CommissionCalculator\Models\Transaction;
use CommissionCalculator\ServiceInterfaces\LegalCashOutCommissionsCalculator as LegalCashOutCommissionsCalculatorInterface;

class LegalCashOutCommissionsCalculator implements LegalCashOutCommissionsCalculatorInterface
{
    const CASH_OUT_RATE = 0.003;
    const CASH_OUT_LEGAL_COMMISSION_MIN = 0.5;

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
        $commissions = $transaction->getAmount() * self::CASH_OUT_RATE;

        if ($this->isBelowMinCashOutCommissions($transaction->getCurrencyCode(), $commissions)) {
            $commissions = $this->currencyConverter->convertFromEUR(
                self::CASH_OUT_LEGAL_COMMISSION_MIN,
                $transaction->getCurrencyCode()
            );
        }

        return $commissions;
    }

    private function isBelowMinCashOutCommissions($currencyCode, $commissions)
    {
        return $this->currencyConverter->convertToEUR(
                $commissions,
                $currencyCode
            ) < self::CASH_OUT_LEGAL_COMMISSION_MIN;
    }
}