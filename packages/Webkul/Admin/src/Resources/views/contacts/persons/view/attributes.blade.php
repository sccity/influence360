{!! view_render_event('admin.contacts.persons.view.attributes.before', ['person' => $person]) !!}

<div class="flex w-full flex-col gap-4 border-b border-gray-200 p-4 dark:border-gray-800">
    <h4 class="font-semibold dark:text-white">
        @lang('admin::app.contacts.persons.view.about-person')
    </h4>

    {!! view_render_event('admin.contacts.persons.view.attributes.form_controls.before', ['person' => $person]) !!}

    <x-admin::form
        v-slot="{ meta, errors, handleSubmit }"
        as="div"
        ref="modalForm"
    >
        <form @submit="handleSubmit($event, () => {})">
            {!! view_render_event('admin.contacts.persons.view.attributes.form_controls.attributes_view.before', ['person' => $person]) !!}

            <x-admin::attributes.view
                :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                    'entity_type' => 'persons',
                    ['code', 'NOTIN', ['name', 'job_title']]
                ])"
                :entity="$person"
                :url="route('admin.contacts.persons.update', $person->id)"
                :allow-edit="true"
            />

            <!-- Address Fields -->
            <div class="flex flex-col gap-2 mt-4">
                <h5 class="font-semibold dark:text-white">Address</h5>
                <div class="flex flex-col gap-1">
                    <p><strong>Street:</strong> {{ $person->street }}</p>
                    <p><strong>City:</strong> {{ $person->city }}</p>
                    <p><strong>State:</strong> {{ $person->state }}</p>
                    <p><strong>ZIP:</strong> {{ $person->zip }}</p>
                </div>
            </div>

            {!! view_render_event('admin.contacts.persons.view.attributes.form_controls.attributes_view.after', ['person' => $person]) !!}
        </form>
    </x-admin::form>

    {!! view_render_event('admin.contacts.persons.view.attributes.form_controls.after', ['person' => $person]) !!}
</div>

{!! view_render_event('admin.contacts.persons.view.attributes.after', ['person' => $person]) !!}
