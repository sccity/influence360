<?php

namespace Webkul\Automation\Helpers\Entity;

use Illuminate\Support\Facades\Mail;
use Webkul\Activity\Repositories\ActivityRepository;
use Webkul\Admin\Notifications\Common;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Automation\Repositories\WebhookRepository;
use Webkul\Automation\Services\WebhookService;
use Webkul\Contact\Repositories\PersonRepository;
use Webkul\EmailTemplate\Repositories\EmailTemplateRepository;
use Webkul\Initiative\Contracts\Initiative as ContractsInitiative;
use Webkul\Initiative\Repositories\InitiativeRepository;
use Webkul\Tag\Repositories\TagRepository;

class Initiative extends AbstractEntity
{
    /**
     * Define the entity type.
     */
    protected string $entityType = 'initiatives';

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected EmailTemplateRepository $emailTemplateRepository,
        protected InitiativeRepository $initiativeRepository,
        protected ActivityRepository $activityRepository,
        protected PersonRepository $personRepository,
        protected TagRepository $tagRepository,
        protected WebhookRepository $webhookRepository,
        protected WebhookService $webhookService
    ) {}

    /**
     * Listing of the entities.
     */
    public function getEntity(mixed $entity)
    {
        if (! $entity instanceof ContractsInitiative) {
            $entity = $this->initiativeRepository->find($entity);
        }

        return $entity;
    }

    /**
     * Returns attributes.
     */
    public function getAttributes(string $entityType, array $skipAttributes = ['textarea', 'image', 'file', 'address']): array
    {
        $attributes[] = [
            'id'          => 'initiative_pipeline_stage_id',
            'type'        => 'select',
            'name'        => 'Stage',
            'lookup_type' => 'initiative_pipeline_stages',
            'options'     => collect(),
        ];

        return array_merge(
            parent::getAttributes($entityType, $skipAttributes),
            $attributes
        );
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
                'id'         => 'update_initiative',
                'name'       => trans('admin::app.settings.workflows.helpers.update-initiative'),
                'attributes' => $this->getAttributes('initiatives'),
            ], [
                'id'         => 'update_person',
                'name'       => trans('admin::app.settings.workflows.helpers.update-person'),
                'attributes' => $this->getAttributes('persons'),
            ], [
                'id'      => 'send_email_to_person',
                'name'    => trans('admin::app.settings.workflows.helpers.send-email-to-person'),
                'options' => $emailTemplates,
            ], [
                'id'      => 'send_email_to_sales_owner',
                'name'    => trans('admin::app.settings.workflows.helpers.send-email-to-sales-owner'),
                'options' => $emailTemplates,
            ], [
                'id'   => 'add_tag',
                'name' => trans('admin::app.settings.workflows.helpers.add-tag'),
            ], [
                'id'   => 'add_note_as_activity',
                'name' => trans('admin::app.settings.workflows.helpers.add-note-as-activity'),
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
    public function executeActions(mixed $workflow, mixed $initiative): void
    {
        foreach ($workflow->actions as $action) {
            switch ($action['id']) {
                case 'update_initiative':
                    $this->initiativeRepository->update([
                        'entity_type'        => 'initiatives',
                        $action['attribute'] => $action['value'],
                    ], $initiative->id);

                    break;

                case 'update_person':
                    $this->personRepository->update([
                        'entity_type'        => 'persons',
                        $action['attribute'] => $action['value'],
                    ], $initiative->person_id);

                    break;

                case 'send_email_to_person':
                    $emailTemplate = $this->emailTemplateRepository->find($action['value']);

                    if (! $emailTemplate) {
                        break;
                    }

                    try {
                        Mail::queue(new Common([
                            'to'      => data_get($initiative->person->emails, '*.value'),
                            'subject' => $this->replacePlaceholders($initiative, $emailTemplate->subject),
                            'body'    => $this->replacePlaceholders($initiative, $emailTemplate->content),
                        ]));
                    } catch (\Exception $e) {
                    }

                    break;

                case 'send_email_to_sales_owner':
                    $emailTemplate = $this->emailTemplateRepository->find($action['value']);

                    if (! $emailTemplate) {
                        break;
                    }

                    try {
                        Mail::queue(new Common([
                            'to'      => $initiative->user->email,
                            'subject' => $this->replacePlaceholders($initiative, $emailTemplate->subject),
                            'body'    => $this->replacePlaceholders($initiative, $emailTemplate->content),
                        ]));
                    } catch (\Exception $e) {
                    }

                    break;

                case 'add_tag':
                    $colors = [
                        '#337CFF',
                        '#FEBF00',
                        '#E5549F',
                        '#27B6BB',
                        '#FB8A3F',
                        '#43AF52',
                    ];

                    if (! $tag = $this->tagRepository->findOneByField('name', $action['value'])) {
                        $tag = $this->tagRepository->create([
                            'name'    => $action['value'],
                            'color'   => $colors[rand(0, 5)],
                            'user_id' => auth()->guard('user')->user()->id,
                        ]);
                    }

                    if (! $initiative->tags->contains($tag->id)) {
                        $initiative->tags()->attach($tag->id);
                    }

                    break;

                case 'add_note_as_activity':
                    $activity = $this->activityRepository->create([
                        'type'    => 'note',
                        'comment' => $action['value'],
                        'is_done' => 1,
                        'user_id' => auth()->guard('user')->user()->id,
                    ]);

                    $initiative->activities()->attach($activity->id);

                    break;

                case 'trigger_webhook':
                    try {
                        $this->triggerWebhook($action['value'], $initiative);
                    } catch (\Exception $e) {
                        report($e);
                    }

                    break;
            }
        }
    }
}
