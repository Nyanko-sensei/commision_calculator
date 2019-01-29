<?php

namespace CommissionCalculator\ServiceInterfaces;

use CommissionCalculator\Models\Transaction;

interface CommissionsCalculator
{
    public function calculateCommissionsForTransaction(Transaction $transaction): float;
}