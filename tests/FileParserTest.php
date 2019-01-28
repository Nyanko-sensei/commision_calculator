<?php

declare(strict_types=1);

use CommissionCalculator\Models\Transaction;
use CommissionCalculator\Services\FileParser;
use PHPUnit\Framework\TestCase;

final class  FileParserTest extends TestCase
{
    /**
     * @test
     */
    public function canGetTransactionsFromCSV(): void
    {

        file_put_contents('test.csv', "2014-12-31,4,natural,cash_out,1200.00,EUR\n");
        file_put_contents('test.csv', "2015-01-04,4,natural,cash_out,1000.00,EUR\n", FILE_APPEND);

        $fileParser = new FileParser();
        $fileParser->setPath('test.csv');
        $fileParser->parse();

        /** @var Transaction[] $transactions */
        $transactions = $fileParser->getTransactions();

        $firstTransaction = $transactions[0];
        $secondTransaction = $transactions[1];

        $this->assertEquals("2014-12-31", $firstTransaction->getDate()->toDateString());
        $this->assertEquals(4, $firstTransaction->getUserId());
        $this->assertEquals("natural", $firstTransaction->getUserType());
        $this->assertEquals("cash_out", $firstTransaction->getOperationType());
        $this->assertEquals(1200, $firstTransaction->getAmount());
        $this->assertEquals("EUR", $firstTransaction->getCurrencyCode());

        $this->assertEquals("2015-01-04", $secondTransaction->getDate()->toDateString());
        $this->assertEquals(4, $secondTransaction->getUserId());
        $this->assertEquals("natural", $secondTransaction->getUserType());
        $this->assertEquals("cash_out", $secondTransaction->getOperationType());
        $this->assertEquals(1000, $secondTransaction->getAmount());
        $this->assertEquals("EUR", $secondTransaction->getCurrencyCode());

        unlink('test.csv');
    }
}