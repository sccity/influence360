@php
    $states = [
        'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas', 'CA' => 'California',
        'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 'FL' => 'Florida', 'GA' => 'Georgia',
        'HI' => 'Hawaii', 'ID' => 'Idaho', 'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa',
        'KS' => 'Kansas', 'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
        'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 'MO' => 'Missouri',
        'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 'NH' => 'New Hampshire', 'NJ' => 'New Jersey',
        'NM' => 'New Mexico', 'NY' => 'New York', 'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio',
        'OK' => 'Oklahoma', 'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
        'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 'VT' => 'Vermont',
        'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 'WI' => 'Wisconsin', 'WY' => 'Wyoming'
    ];
@endphp

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

            <!-- Address Section -->
            <div class="mb-4">
                <h5 class="font-medium text-gray-800 dark:text-gray-300 mb-2">
                    @lang('admin::app.contacts.persons.view.address')
                </h5>
                <div class="flex flex-col gap-1 text-gray-600 dark:text-gray-400">
                    @if ($person->street)
                        <p>{{ $person->street }}</p>
                    @endif
                    @if ($person->city || $person->state || $person->zip)
                        <p>
                            {{ $person->city }}{{ $person->city && ($person->state || $person->zip) ? ',' : '' }}
                            {{ $person->state }} {{ $person->zip }}
                        </p>
                    @endif
                </div>
            </div>

            <x-admin::attributes.view
                :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                    'entity_type' => 'persons',
                    ['code', 'NOTIN', ['name', 'job_title']]
                ])"
                :entity="$person"
                :url="route('admin.contacts.persons.update', $person->id)"
                :allow-edit="true"
            >
                <!-- Override the state field to be a dropdown -->
                <x-slot name="state">
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>State</x-admin::form.control-group.label>
                        <x-admin::form.control-group.control
                            type="select"
                            name="state"
                            :value="$person->state"
                        >
                            <option value="">Select a state</option>
                            @foreach ($states as $abbr => $stateName)
                                <option value="{{ $abbr }}" {{ $person->state == $abbr ? 'selected' : '' }}>
                                    {{ $stateName }}
                                </option>
                            @endforeach
                        </x-admin::form.control-group.control>
                    </x-admin::form.control-group>
                </x-slot>
            </x-admin::attributes.view>

            {!! view_render_event('admin.contacts.persons.view.attributes.form_controls.attributes_view.after', ['person' => $person]) !!}
        </form>
    </x-admin::form>

    {!! view_render_event('admin.contacts.persons.view.attributes.form_controls.after', ['person' => $person]) !!}
</div>

{!! view_render_event('admin.contacts.persons.view.attributes.after', ['person' => $person]) !!}
