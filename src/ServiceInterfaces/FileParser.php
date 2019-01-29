<?php

namespace CommissionCalculator\ServiceInterfaces;

interface FileParser
{
    public function setPath(string $path): void;

    public function parse(): void;

    public function getTransactions(): array;
}