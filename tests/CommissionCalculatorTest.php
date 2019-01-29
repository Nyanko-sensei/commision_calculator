<?php

declare(strict_types=1);

use Carbon\Carbon;
use CommissionCalculator\Models\Transaction;
use CommissionCalculator\Services\CashInCommissionsCalculator;
use CommissionCalculator\Services\CommissionCalculator;
use CommissionCalculator\Services\CurrencyConverter;
use CommissionCalculator\Services\CurrencyRepository;
use CommissionCalculator\Services\LegalCashOutCommissionsCalculator;
use CommissionCalculator\Services\NaturalCashOutCommissionsCalculator;
use PHPUnit\Framework\TestCase;

final class  CommissionCalculatorTest extends TestCase
{
    /**
     * @return array
     */
    private function getPayload(): array
    {
        $transactions = [];
        $lines = [
            '2014-12-31,4,natural,cash_out,1200.00,EUR',
            '2015-01-01,4,natural,cash_out,1000.00,EUR',
            '2016-01-05,4,natural,cash_out,1000.00,EUR',
            '2016-01-05,1,natural,cash_in,200.00,EUR',
            '2016-01-06,2,legal,cash_out,300.00,EUR',
            '2016-01-06,1,natural,cash_out,30000,JPY',
            '2016-01-07,1,natural,cash_out,1000.00,EUR',
            '2016-01-07,1,natural,cash_out,100.00,USD',
            '2016-01-10,1,natural,cash_out,100.00,EUR',
            '2016-01-10,2,legal,cash_in,1000000.00,EUR',
            '2016-01-10,3,natural,cash_out,1000.00,EUR',
            '2016-02-15,1,natural,cash_out,300.00,EUR',
            '2016-02-19,5,natural,cash_out,3000000,JPY',
        ];

        foreach ($lines as $line) {
            $transactions[] = $this->lineToTransaction($line);
        }

        return $transactions;
    }

    private function getExpectedOutput(): array
    {
        return [
            0.60,
            3.00,
            0.00,
            0.06,
            0.90,
            0,
            0.70,
            0.30,
            0.30,
            5.00,
            0.00,
            0.00,
            8612,
        ];
    }

    /**
     * @group sp
     * @test
     */
    public function testingTaskSet(): void
    {
        $transactions = $this->getPayload();
        $results = $this->getExpectedOutput();
        $currencyConverter = new CurrencyConverter(new CurrencyRepository());

        $commissionsCalculator = new CommissionCalculator(new CashInCommissionsCalculator($currencyConverter),
            new LegalCashOutCommissionsCalculator($currencyConverter),
            new NaturalCashOutCommissionsCalculator($currencyConverter), new CurrencyRepository());

        foreach ($transactions as $key => $transaction) {
            $this->assertEquals($results[$key],
                $commissionsCalculator->calculateCommissionsForTransaction($transaction));
        }
    }

    /**
     * @test
     */
    public function cashInCommissions()
    {
        $commissionsCalculator = new CashInCommissionsCalculator(new CurrencyConverter(new CurrencyRepository()));

        $transaction = $this->lineToTransaction('2014-12-31,4,natural,cash_in,1000.00,EUR');
        $this->assertEquals(0.3, $commissionsCalculator->calculateCommissionsForTransaction($transaction));

        $transaction = $this->lineToTransaction('2014-12-31,4,natural,cash_in,1000000.00,EUR');
        $this->assertEquals(5, $commissionsCalculator->calculateCommissionsForTransaction($transaction));

        $transaction = $this->lineToTransaction('2014-12-31,4,natural,cash_in,1000.00,JPY');
        $this->assertEquals(0.3, $commissionsCalculator->calculateCommissionsForTransaction($transaction));


        $transaction = $this->lineToTransaction('2014-12-31,4,natural,cash_in,100000000.00,JPY');
        $this->assertEquals(647.65, $commissionsCalculator->calculateCommissionsForTransaction($transaction));
    }

    /**
     * @test
     */
    public function legalCashOutCommissions()
    {
        $commissionsCalculator = new LegalCashOutCommissionsCalculator(new CurrencyConverter(new CurrencyRepository()));

        $transaction = $this->lineToTransaction('2014-12-31,4,legal,cash_out,1000.00,EUR');
        $this->assertEquals(3, $commissionsCalculator->calculateCommissionsForTransaction($transaction));

        $transaction = $this->lineToTransaction('2014-12-31,4,legal,cash_out,1.00,EUR');
        $this->assertEquals(0.5, $commissionsCalculator->calculateCommissionsForTransaction($transaction));

        $transaction = $this->lineToTransaction('2014-12-31,4,legal,cash_out,100000.00,JPY');
        $this->assertEquals(300, $commissionsCalculator->calculateCommissionsForTransaction($transaction));


        $transaction = $this->lineToTransaction('2014-12-31,4,natural,cash_in,10.00,JPY');
        $this->assertEquals(64.765, $commissionsCalculator->calculateCommissionsForTransaction($transaction));
    }

    /**
     * @test
     */
    public function naturalCashOutCommissions()
    {
        $commissionsCalculator = new NaturalCashOutCommissionsCalculator(new CurrencyConverter(new CurrencyRepository()));

        // first 1000 in week is free*
        $transaction = $this->lineToTransaction('2014-12-31,1,natural,cash_out,1000.00,EUR');
        $this->assertEquals(0, $commissionsCalculator->calculateCommissionsForTransaction($transaction));

        // after first 1000 in week it costs  0.3%
        $transaction = $this->lineToTransaction('2015-01-04,1,natural,cash_out,1000.00,EUR');
        $this->assertEquals(3, $commissionsCalculator->calculateCommissionsForTransaction($transaction));

        // first 1000 in week is free*
        $transaction = $this->lineToTransaction('2015-01-05,1,natural,cash_out,1000.00,EUR');
        $this->assertEquals(0, $commissionsCalculator->calculateCommissionsForTransaction($transaction));


        // 4th cash out costs 0.3%
        $transaction = $this->lineToTransaction('2015-03-01,1,natural,cash_out,50.00,EUR');
        $this->assertEquals(0, $commissionsCalculator->calculateCommissionsForTransaction($transaction));
        $this->assertEquals(0, $commissionsCalculator->calculateCommissionsForTransaction($transaction));
        $this->assertEquals(0, $commissionsCalculator->calculateCommissionsForTransaction($transaction));

        $transaction = $this->lineToTransaction('2015-03-01,1,natural,cash_out,1000.00,EUR');
        $this->assertEquals(3, $commissionsCalculator->calculateCommissionsForTransaction($transaction));
    }

    /**
     * @param $line
     *
     * @return Transaction
     */
    private function lineToTransaction($line)
    {
        $properties = explode(',', $line);

        $transaction = new Transaction();
        $transaction->setDate(new Carbon($properties[0]));
        $transaction->setUserId((int)$properties[1]);
        $transaction->setUserType($properties[2]);
        $transaction->setOperationType($properties[3]);
        $transaction->setAmount((float)$properties[4]);
        $transaction->setCurrencyCode($properties[5]);

        return $transaction;
    }
}