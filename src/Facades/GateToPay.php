<?php

namespace ASCIISD\GateToPay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getCustomerCards(?string $customerId = null)
 * @method static array cardCashOut(array $params)
 * @method static array cardCashIn(array $params)
 *
 * @see \ASCIISD\GateToPay\Services\GateToPayService
 */
class GateToPay extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'gatetopay';
    }
}
