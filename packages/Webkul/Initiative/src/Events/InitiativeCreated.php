<?php

namespace Webkul\Initiative\Events;

use Illuminate\Queue\SerializesModels;
use Webkul\Initiative\Contracts\Initiative;

class InitiativeCreated
{
    use SerializesModels;

    /**
     * The initiative instance.
     *
     * @var \Webkul\Initiative\Contracts\Initiative
     */
    public $initiative;

    /**
     * Create a new event instance.
     *
     * @param  \Webkul\Initiative\Contracts\Initiative  $initiative
     * @return void
     */
    public function __construct(Initiative $initiative)
    {
        $this->initiative = $initiative;
    }
}
