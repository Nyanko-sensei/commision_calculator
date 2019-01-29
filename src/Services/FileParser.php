<?php

namespace CommissionCalculator\Services;

use Carbon\Carbon;
use CommissionCalculator\Models\Transaction;
use CommissionCalculator\ServiceInterfaces\FileParser as FileParserInterface;

class FileParser implements FileParserInterface
{
    /**
     * @var Transaction[]
     */
    private $transactions = [];
    /**
     * @var string
     */
    private $path;

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function parse(): void
    {
        $this->transactions = [];

        if (file_exists($this->path)) {
            $file = fopen($this->path, "r");

            while (! feof($file)) {
                $line = fgetcsv($file);
                if ($line) {
                    $transaction = new Transaction();
                    $transaction->setDate(new Carbon($line[0]));
                    $transaction->setUserId((int)$line[1]);
                    $transaction->setUserType($line[2]);
                    $transaction->setOperationType($line[3]);
                    $transaction->setAmount((float)$line[4]);
                    $transaction->setCurrencyCode($line[5]);

                    $this->transactions[] = $transaction;
                };
            }

            fclose($file);
        }
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }
}