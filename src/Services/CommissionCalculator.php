<?php
namespace CommissionCalculator\Services;

use CommissionCalculator\Models\Transaction;
use CommissionCalculator\ServiceInterfaces\CashInCommissionsCalculator;
use CommissionCalculator\ServiceInterfaces\LegalCashOutCommissionsCalculator;
use CommissionCalculator\ServiceInterfaces\NaturalCashOutCommissionsCalculator;
use CommissionCalculator\ServiceInterfaces\CommissionsCalculator as CommissionCalculatorInterface;

class CommissionCalculator implements CommissionCalculatorInterface
{
    const OPERATION_CASH_IN = 'cash_in';
    const OPERATION_CASH_OUT = 'cash_out';

    const USER_TYPE_LEGAL = 'legal';
    const USER_TYPE_NATURAL = 'natural';

    private $cashInCommissionCalculator;
    private $legalCashOutCommissionCalculator;
    private $naturalCashOutCommissionCalculator;
    private $currencyRepository;

    public function __construct(
        CashInCommissionsCalculator $cashInCommissionCalculator,
        LegalCashOutCommissionsCalculator $legalCashOutCommissionCalculator,
        NaturalCashOutCommissionsCalculator $naturalCashOutCommissionCalculator,
        CurrencyRepository $currencyRepository
    )
    {
        $this->cashInCommissionCalculator = $cashInCommissionCalculator;
        $this->legalCashOutCommissionCalculator = $legalCashOutCommissionCalculator;
        $this->naturalCashOutCommissionCalculator = $naturalCashOutCommissionCalculator;
        $this->currencyRepository = $currencyRepository;
    }

    public function calculateCommissionsForTransaction(Transaction $transaction): float
    {
        $commissions = 0;

        if ($transaction->getOperationType() == self::OPERATION_CASH_IN) {
            $commissions= $this->cashInCommissionCalculator->calculateCommissionsForTransaction($transaction);
        } elseif ($transaction->getOperationType() == self::OPERATION_CASH_OUT) {
            if ($transaction->getUserType() == self::USER_TYPE_LEGAL) {
                $commissions = $this->legalCashOutCommissionCalculator->calculateCommissionsForTransaction($transaction);
            } elseif ($transaction->getUserType() == self::USER_TYPE_NATURAL) {
                $commissions = $this->naturalCashOutCommissionCalculator->calculateCommissionsForTransaction($transaction);
            }

        }

        $commissions = $this->roundUp($commissions, $transaction->getCurrencyCode());

        return $commissions;
    }

    public function roundUp($commissions, $currencyCode)
    {
        $currency = $this->currencyRepository->getCurrencyByCode($currencyCode);
        $multiplier = pow(10, $currency->getDecimalPlaces());
        return ceil($commissions * $multiplier)/$multiplier;
    }
}