<?php

namespace Webkul\Admin\Listeners;

use Webkul\Email\Repositories\EmailRepository;

class Initiative
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected EmailRepository $emailRepository) {}

    /**
     * @param  \Webkul\Initiative\Models\Initiative  $initiative
     * @return void
     */
    public function linkToEmail($initiative)
    {
        if (! request('email_id')) {
            return;
        }

        $this->emailRepository->update([
            'initiative_id' => $initiative->id,
        ], request('email_id'));
    }
}
