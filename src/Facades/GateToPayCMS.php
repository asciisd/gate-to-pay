<?php

namespace ASCIISD\GateToPay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array createNewProfile(array $params)
 * @method static string generateCustomerId()
 *
 * @see \ASCIISD\GateToPay\Services\CMSApiService
 */
class GateToPayCMS extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'gatetopay.cms';
    }
}
