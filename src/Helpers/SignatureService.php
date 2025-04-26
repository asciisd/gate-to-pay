<?php

namespace ASCIISD\GateToPay\Helpers;

use Illuminate\Support\Facades\Log;

class SignatureService
{
    /**
     * Create a new SignatureService instance.
     *
     * @param string $apiKey
     * @return void
     */
    public function __construct(protected string $apiKey)
    {
        //
    }

    /**
     * Generate a signature for the given data.
     *
     * @param string $str
     * @return string
     */
    public function generate(string $str): string
    {
        // Log the input data for signature generation
        if (config('gatetopay.logging.enabled', true)) {
            Log::channel(config('gatetopay.logging.channel', 'gatetopay'))
                ->info('Generating signature for data: '.$str);
        }

        $data = md5($str, true);
        $signature = $this->encryptTripleDES($data, $this->apiKey);

        // Log the generated signature
        if (config('gatetopay.logging.enabled', true)) {
            Log::channel(config('gatetopay.logging.channel', 'gatetopay'))
                ->info('Generated signature: '.$signature);
        }

        return $signature;
    }

    /**
     * Encrypt data using TripleDES with ECB mode, no padding.
     *
     * @param string $data
     * @param string $key
     * @return string
     */
    protected function encryptTripleDES(string $data, string $key): string
    {
        $key = str_replace('-', '', $this->apiKey);
        $key = hex2bin($key);
        $key .= substr($key, 0, 8);
        $encrypted = openssl_encrypt($data, 'DES-EDE3', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING);

        return strtoupper(bin2hex($encrypted));
    }
}
