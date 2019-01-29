<?php

namespace CommissionCalculator\Models;

use Carbon\Carbon;

class User
{
    /** @var  int */
    private $id;
    /** @var  Carbon|null */
    private $lastTransactionDate;
    /** @var  float */
    private $cashOutTotalForLastKnownWeek;
    /** @var  int */
    private $cashOutTimesForLastKnownWeek;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return Carbon|null
     */
    public function getLastTransactionDate()
    {
        return $this->lastTransactionDate;
    }

    /**
     * @param Carbon $lastTransactionDate
     */
    public function setLastTransactionDate(Carbon $lastTransactionDate)
    {
        $this->lastTransactionDate = $lastTransactionDate;
    }

    /**
     * @return float
     */
    public function getCashOutTotalForLastKnownWeek(): float
    {
        return $this->cashOutTotalForLastKnownWeek;
    }

    /**
     * @param float $cashOutTotalForLastKnownWeek
     */
    public function setCashOutTotalForLastKnownWeek(float $cashOutTotalForLastKnownWeek)
    {
        $this->cashOutTotalForLastKnownWeek = $cashOutTotalForLastKnownWeek;
    }

    /**
     * @return int
     */
    public function getCashOutTimesForLastKnownWeek(): int
    {
        return $this->cashOutTimesForLastKnownWeek;
    }

    /**
     * @param int $cashOutTimesForLastKnownWeek
     */
    public function setCashOutTimesForLastKnownWeek(int $cashOutTimesForLastKnownWeek)
    {
        $this->cashOutTimesForLastKnownWeek = $cashOutTimesForLastKnownWeek;
    }
}