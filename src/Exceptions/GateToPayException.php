<?php

namespace ASCIISD\GateToPay\Exceptions;

use Exception;

class GateToPayException extends Exception
{
    // Custom exception codes
    const OTP_REQUIRED = 1001;

    /**
     * Create a new GateToPayException instance.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     * @return void
     */
    public function __construct(string $message = '', int $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Check if OTP is required.
     *
     * @return bool
     */
    public function isOtpRequired(): bool
    {
        return $this->getCode() === self::OTP_REQUIRED;
    }
}
