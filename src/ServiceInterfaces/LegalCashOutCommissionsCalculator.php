<?php

namespace CommissionCalculator\ServiceInterfaces;

/**
 * Interface LegalCashOutCommissionsCalculator
 *
 * This interface extends main commission calculator without adding any new methods.
 * It is done to enable auto wiring and so specific case implementation
 * could be easily interchangeable (in app/bootstrap.php)
 *
 * @package CommissionCalculator\ServiceInterfaces
 */
interface LegalCashOutCommissionsCalculator extends CommissionsCalculator
{
}