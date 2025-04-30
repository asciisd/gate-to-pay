<?php

namespace ASCIISD\GateToPay\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProfileUpdated
{
    use Dispatchable, SerializesModels;

    /**
     * The profile data.
     *
     * @var array
     */
    public $profileData;

    /**
     * Create a new event instance.
     *
     * @param array $profileData
     * @return void
     */
    public function __construct(array $profileData)
    {
        $this->profileData = $profileData;
    }
}
