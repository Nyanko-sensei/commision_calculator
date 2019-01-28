<?php

use Symfony\Component\DependencyInjection\ContainerBuilder;

$containerBuilder = new ContainerBuilder();

$containerBuilder->autowire(CommissionCalculator\Services\FileParser::class);
$containerBuilder->setAlias(
    CommissionCalculator\ServiceInterfaces\FileParser::class,
    CommissionCalculator\Services\FileParser::class
)->setPublic(true);

$containerBuilder->autowire(CommissionCalculator\Services\CurrencyConverter::class);
$containerBuilder->setAlias(
    CommissionCalculator\ServiceInterfaces\CurrencyConverter::class,
    CommissionCalculator\Services\CurrencyConverter::class
);

$containerBuilder->autowire(CommissionCalculator\Services\STDLogger::class);
$containerBuilder->setAlias(
    CommissionCalculator\ServiceInterfaces\Logger::class,
    CommissionCalculator\Services\STDLogger::class
)->setPublic(true);

$containerBuilder->autowire(CommissionCalculator\Services\CommissionCalculator::class);
$containerBuilder->setAlias(
    CommissionCalculator\ServiceInterfaces\CommissionsCalculator::class,
    CommissionCalculator\Services\CommissionCalculator::class
)->setPublic(true);

$containerBuilder->autowire(CommissionCalculator\Services\CurrencyRepository::class);
$containerBuilder->setAlias(
    CommissionCalculator\ServiceInterfaces\CurrencyRepository::class,
    CommissionCalculator\Services\CurrencyRepository::class
)->setPublic(true);

$containerBuilder->autowire(CommissionCalculator\Services\CashInCommissionsCalculator::class);
$containerBuilder->setAlias(
    CommissionCalculator\ServiceInterfaces\CashInCommissionsCalculator::class,
    CommissionCalculator\Services\CashInCommissionsCalculator::class
)->setPublic(true);

$containerBuilder->autowire(CommissionCalculator\Services\LegalCashOutCommissionsCalculator::class);
$containerBuilder->setAlias(
    CommissionCalculator\ServiceInterfaces\LegalCashOutCommissionsCalculator::class,
    CommissionCalculator\Services\LegalCashOutCommissionsCalculator::class
);

$containerBuilder->autowire(CommissionCalculator\Services\LegalCashOutCommissionsCalculator::class);
$containerBuilder->setAlias(
    CommissionCalculator\ServiceInterfaces\LegalCashOutCommissionsCalculator::class,
    CommissionCalculator\Services\LegalCashOutCommissionsCalculator::class
);

$containerBuilder->autowire(CommissionCalculator\Services\NaturalCashOutCommissionsCalculator::class);
$containerBuilder->setAlias(
    CommissionCalculator\ServiceInterfaces\NaturalCashOutCommissionsCalculator::class,
    CommissionCalculator\Services\NaturalCashOutCommissionsCalculator::class
);


$containerBuilder->compile();

return $containerBuilder;