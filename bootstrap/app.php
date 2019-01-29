<?php

/**
 * There we register classes, interfaces and which class to use for which interface.
 * It's done, to enable auto wiring and make interface implementations easily changeable
 */

use Symfony\Component\DependencyInjection\ContainerBuilder;

$containerBuilder = new ContainerBuilder();

// file parse
$containerBuilder->autowire(CommissionCalculator\Services\FileParser::class);
$containerBuilder->setAlias(CommissionCalculator\ServiceInterfaces\FileParser::class,
    CommissionCalculator\Services\FileParser::class)->setPublic(true);

// currency converter
$containerBuilder->autowire(CommissionCalculator\Services\CurrencyConverter::class);
$containerBuilder->setAlias(CommissionCalculator\ServiceInterfaces\CurrencyConverter::class,
    CommissionCalculator\Services\CurrencyConverter::class);

// output
$containerBuilder->autowire(CommissionCalculator\Services\STDLogger::class);
$containerBuilder->setAlias(CommissionCalculator\ServiceInterfaces\Logger::class,
    CommissionCalculator\Services\STDLogger::class)->setPublic(true);

// main commission calculator
$containerBuilder->autowire(CommissionCalculator\Services\CommissionCalculator::class);
$containerBuilder->setAlias(CommissionCalculator\ServiceInterfaces\CommissionsCalculator::class,
    CommissionCalculator\Services\CommissionCalculator::class)->setPublic(true);

// currency repository
$containerBuilder->autowire(CommissionCalculator\Services\CurrencyRepository::class);
$containerBuilder->setAlias(CommissionCalculator\ServiceInterfaces\CurrencyRepository::class,
    CommissionCalculator\Services\CurrencyRepository::class)->setPublic(true);

// specific commissions calculators (cash in, legal cash out, natural cash out)
$containerBuilder->autowire(CommissionCalculator\Services\CashInCommissionsCalculator::class);
$containerBuilder->setAlias(CommissionCalculator\ServiceInterfaces\CashInCommissionsCalculator::class,
    CommissionCalculator\Services\CashInCommissionsCalculator::class)->setPublic(true);

$containerBuilder->autowire(CommissionCalculator\Services\LegalCashOutCommissionsCalculator::class);
$containerBuilder->setAlias(CommissionCalculator\ServiceInterfaces\LegalCashOutCommissionsCalculator::class,
    CommissionCalculator\Services\LegalCashOutCommissionsCalculator::class);

$containerBuilder->autowire(CommissionCalculator\Services\NaturalCashOutCommissionsCalculator::class);
$containerBuilder->setAlias(CommissionCalculator\ServiceInterfaces\NaturalCashOutCommissionsCalculator::class,
    CommissionCalculator\Services\NaturalCashOutCommissionsCalculator::class);


$containerBuilder->compile();

return $containerBuilder;