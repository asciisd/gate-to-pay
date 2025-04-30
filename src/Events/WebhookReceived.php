<?php

namespace ASCIISD\GateToPay\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebhookReceived
{
    use Dispatchable, SerializesModels;

    /**
     * The webhook data.
     *
     * @var array
     */
    public $data;

    /**
     * The action type (created, updated, deleted).
     *
     * @var string
     */
    public $action;

    /**
     * Create a new event instance.
     *
     * @param array $data
     * @param string $action
     * @return void
     */
    public function __construct(array $data, string $action)
    {
        $this->data = $data;
        $this->action = $action;
    }
}
