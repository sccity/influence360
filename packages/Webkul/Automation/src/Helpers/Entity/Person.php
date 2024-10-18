<?php

namespace Webkul\Automation\Helpers\Entity;

use Illuminate\Support\Facades\Mail;
use Webkul\Admin\Notifications\Common;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Automation\Contracts\Workflow;
use Webkul\Automation\Repositories\WebhookRepository;
use Webkul\Automation\Services\WebhookService;
use Webkul\Contact\Contracts\Person as PersonContract;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\EmailTemplate\Repositories\EmailTemplateRepository;
use Webkul\Initiative\Repositories\InitiativeRepository;

class Person extends AbstractEntity
{
    /**
     * Define the entity type.
     */
    protected string $entityType = 'persons';

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected EmailTemplateRepository $emailTemplateRepository,
        protected InitiativeRepository $initiativeRepository,
        protected PersonRepository $personRepository,
        protected WebhookRepository $webhookRepository,
        protected WebhookService $webhookService
    ) {}

    /**
     * Listing of the entities.
     */
    public function getEntity(mixed $entity): mixed
    {
        if (! $entity instanceof PersonContract) {
            $entity = $this->personRepository->find($entity);
        }

        return $entity;
    }

    /**
     * Returns workflow actions.
     */
    public function getActions(): array
    {
        $emailTemplates = $this->emailTemplateRepository->all(['id', 'name']);

        $webhooksOptions = $this->webhookRepository->all(['id', 'name']);

        return [
            [
                'id'         => 'update_person',
                'name'       => trans('admin::app.settings.workflows.helpers.update-person'),
                'attributes' => $this->getAttributes('persons'),
            ], [
                'id'         => 'update_related_initiatives',
                'name'       => trans('admin::app.settings.workflows.helpers.update-related-initiatives'),
                'attributes' => $this->getAttributes('initiatives'),
            ], [
                'id'      => 'send_email_to_person',
                'name'    => trans('admin::app.settings.workflows.helpers.send-email-to-person'),
                'options' => $emailTemplates,
            ], [
                'id'      => 'trigger_webhook',
                'name'    => trans('admin::app.settings.workflows.helpers.add-webhook'),
                'options' => $webhooksOptions,
            ],
        ];
    }

    /**
     * Execute workflow actions.
     */
    public function executeActions(mixed $workflow, mixed $person): void
    {
        foreach ($workflow->actions as $action) {
            switch ($action['id']) {
                case 'update_person':
                    $this->personRepository->update([
                        'entity_type'        => 'persons',
                        $action['attribute'] => $action['value'],
                    ], $person->id);

                    break;

                case 'update_related_initiatives':
                    $initiatives = $this->initiativeRepository->findByField('person_id', $person->id);

                    foreach ($initiatives as $initiative) {
                        $this->initiativeRepository->update([
                            'entity_type'        => 'initiatives',
                            $action['attribute'] => $action['value'],
                        ], $initiative->id);
                    }

                    break;

                case 'send_email_to_person':
                    $emailTemplate = $this->emailTemplateRepository->find($action['value']);

                    if (! $emailTemplate) {
                        break;
                    }

                    try {
                        Mail::queue(new Common([
                            'to'      => data_get($person->emails, '*.value'),
                            'subject' => $this->replacePlaceholders($person, $emailTemplate->subject),
                            'body'    => $this->replacePlaceholders($person, $emailTemplate->content),
                        ]));
                    } catch (\Exception $e) {
                        report($e);
                    }

                    break;

                case 'trigger_webhook':
                    try {
                        $this->triggerWebhook($action['value'], $person);
                    } catch (\Exception $e) {
                        report($e);
                    }

                    break;
            }
        }
    }
}
