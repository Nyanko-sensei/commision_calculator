<?php

namespace CommissionCalculator\ServiceInterfaces;

/**
 * Interface NaturalCashOutCommissionsCalculator
 *
 * This interface extends main commission calculator without adding any new methods.
 * It is done to enable auto wiring and so specific case implementation
 * could be easily interchangeable (in app/bootstrap.php)
 *
 * @package CommissionCalculator\ServiceInterfaces
 */
interface NaturalCashOutCommissionsCalculator extends CommissionsCalculator
{
}