<?php

namespace ASCIISD\GateToPay\Helpers;

use Illuminate\Support\Facades\Log;

class SignatureService
{
    /**
     * The API key.
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Create a new SignatureService instance.
     *
     * @param string $apiKey
     * @return void
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * Generate a signature for the given data.
     *
     * @param string $data
     * @return string
     */
    public function generate(string $data): string
    {
        // Log the input data for signature generation
        if (config('gatetopay.logging.enabled', true)) {
            Log::channel(config('gatetopay.logging.channel', 'gatetopay'))
                ->info('Generating signature for data: ' . $data);
        }

        // Step 1: Create MD5 hash of the input data
        $md5Hash = md5($data);

        // Step 2: Prepare the encryption key (API key without dashes, used as hex)
        $key = str_replace('-', '', $this->apiKey);

        // Step 3: Encrypt using TripleDES with ECB mode, no padding
        $encryptedData = $this->encryptTripleDES($md5Hash, $key);

        // Log the generated signature
        if (config('gatetopay.logging.enabled', true)) {
            Log::channel(config('gatetopay.logging.channel', 'gatetopay'))
                ->info('Generated signature: ' . $encryptedData);
        }

        return $encryptedData;
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
        // Convert key to binary
        $keyBin = hex2bin($key);
        
        // Use first 24 bytes of the key (TripleDES requires 24 bytes)
        $keyBin = substr($keyBin . $keyBin, 0, 24);
        
        // Encrypt using TripleDES with ECB mode, no padding
        $encrypted = openssl_encrypt(
            $data,
            'des-ede3',
            $keyBin,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING
        );
        
        // Convert to hexadecimal string
        return bin2hex($encrypted);
    }
}
