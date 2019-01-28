<?php
namespace CommissionCalculator\Services;

use CommissionCalculator\Models\Transaction;
use CommissionCalculator\Models\User;
use CommissionCalculator\ServiceInterfaces\NaturalCashOutCommissionsCalculator as NaturalCashOutCommissionsCalculatorInterface;

class NaturalCashOutCommissionsCalculator implements NaturalCashOutCommissionsCalculatorInterface
{
    const CASH_OUT_RATE = 0.003;
    const CASH_OUT_NATURAL_FREE_FROM_COMMISSION_AMOUNT = 1000;
    const CASH_OUT_NATURAL_FREE_FROM_COMMISSION_TIMES = 3;

    /** @var CurrencyConverter */
    private $currencyConverter;
    /** @var User[]  */
    private $users = [];

    public function __construct(CurrencyConverter $currencyConverter)
    {
        $this->currencyConverter = $currencyConverter;
    }

    public function calculateCommissionsForTransaction(Transaction $transaction): float
    {
        $user = $this->getUser($transaction->getUserId());

        $notCommissionablePart = $this->getRemainigNotCommissionablePart($user, $transaction);


        $notCommissionablePartInCurrency = $this->currencyConverter->convertFromEUR($notCommissionablePart, $transaction->getCurrencyCode());

        $commissionableAmount =  0;
        if ($transaction->getAmount() > $notCommissionablePartInCurrency) {
            $commissionableAmount = $transaction->getAmount() - $notCommissionablePartInCurrency;
        }

        if ($this->isTransactionInNewWeek($user, $transaction)) {
            $user->setLastTransactionDate($transaction->getDate());
            $user->setCashOutTotalForLastKnownWeek($this->currencyConverter->convertToEUR($transaction->getAmount(), $transaction->getCurrencyCode()));
            $user->setCashOutTimesForLastKnownWeek(1);
        } else {
            $user->setLastTransactionDate($transaction->getDate());
            $user->setCashOutTotalForLastKnownWeek($user->getCashOutTotalForLastKnownWeek() + $this->currencyConverter->convertToEUR($transaction->getAmount(), $transaction->getCurrencyCode()));
            $user->setCashOutTimesForLastKnownWeek($user->getCashOutTimesForLastKnownWeek() + 1);
        }


        $this->setUser($user);

        return $commissionableAmount * self::CASH_OUT_RATE;
    }

    private function getUser($userId)
    {
        $user = $this->users[$userId] ?? null;

        if (!$user) {

            if (!$user) {
                $user = new User();
                $user->setId($userId);
                $user->setCashOutTotalForLastKnownWeek(0);
                $user->setCashOutTimesForLastKnownWeek(0);

                $this->users[$user->getId()] = $user;
            }
        }

        return $user;
    }


    private function setUser(User $user)
    {
        $this->users[$user->getId()] = $user;
    }

    private function getRemainigNotCommissionablePart(User $user,Transaction $transaction)
    {
        if ($this->isTransactionInNewWeek($user, $transaction)) {
            return self::CASH_OUT_NATURAL_FREE_FROM_COMMISSION_AMOUNT;
        }

        if ($user->getCashOutTimesForLastKnownWeek() >= self::CASH_OUT_NATURAL_FREE_FROM_COMMISSION_TIMES)
        {
            return 0;
        }

        if ($user->getCashOutTotalForLastKnownWeek() >= self::CASH_OUT_NATURAL_FREE_FROM_COMMISSION_AMOUNT)
        {
            return 0;
        }


        return  self::CASH_OUT_NATURAL_FREE_FROM_COMMISSION_AMOUNT  - $user->getCashOutTotalForLastKnownWeek();
    }

    private function  isTransactionInNewWeek(User $user,Transaction $transaction)
    {
        if (!$user->getLastTransactionDate()) {
           return true;
        }

        return $user->getLastTransactionDate()
            ->startOfWeek()->isBefore(
                $transaction->getDate()->startOfWeek()
            );
    }
}