{!! view_render_event('admin.initiatives.view.person.before', ['initiative' => $initiative]) !!}

@if ($initiative?->person)
    <div class="flex w-full flex-col gap-4 border-b border-gray-200 p-4 dark:border-gray-800">
        <h4 class="flex items-center justify-between font-semibold dark:text-white">
            @lang('admin::app.initiatives.view.persons.title')

            @if (bouncer()->hasPermission('contacts.persons.edit'))
                <a
                    href="{{ route('admin.contacts.persons.edit', $initiative->person->id) }}"
                    class="icon-edit rounded-md p-1 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                    target="_blank"
                ></a>
            @endif
        </h4>

        <div class="flex gap-2">
            {!! view_render_event('admin.initiatives.view.person.avatar.before', ['initiative' => $initiative]) !!}

            <!-- Person Initials -->
            <x-admin::avatar :name="$initiative->person->name" />

            {!! view_render_event('admin.initiatives.view.person.avatar.after', ['initiative' => $initiative]) !!}

            <!-- Person Details -->
            <div class="flex flex-col gap-1">
                {!! view_render_event('admin.initiatives.view.person.name.before', ['initiative' => $initiative]) !!}

                <a
                    href="{{ route('admin.contacts.persons.view', $initiative->person->id) }}"
                    class="font-semibold text-brandColor"
                    target="_blank"
                >
                    {{ $initiative->person->name }}
                </a>

                {!! view_render_event('admin.initiatives.view.person.name.after', ['initiative' => $initiative]) !!}

                {!! view_render_event('admin.initiatives.view.person.job_title.before', ['initiative' => $initiative]) !!}

                @if ($initiative->person->job_title)
                    <span class="dark:text-white">
                        @if ($initiative->person->organization)
                            @lang('admin::app.initiatives.view.persons.job-title', [
                                'job_title'    => $initiative->person->job_title,
                                'organization' => $initiative->person->organization->name
                            ])
                        @else
                            {{ $initiative->person->job_title }}
                        @endif
                    </span>
                @endif

                {!! view_render_event('admin.initiatives.view.person.job_title.after', ['initiative' => $initiative]) !!}
            
                {!! view_render_event('admin.initiatives.view.person.email.before', ['initiative' => $initiative]) !!}

                @foreach ($initiative->person->emails as $email)
                    <div class="flex gap-1">
                        <a 
                            class="text-brandColor"
                            href="mailto:{{ $email['value'] }}"
                        >
                            {{ $email['value'] }}
                        </a>

                        <span class="text-gray-500 dark:text-gray-300">
                            ({{ $email['label'] }})
                        </span>
                    </div>
                @endforeach

                {!! view_render_event('admin.initiatives.view.person.email.after', ['initiative' => $initiative]) !!}

                {!! view_render_event('admin.initiatives.view.person.contact_numbers.before', ['initiative' => $initiative]) !!}
            
                @foreach ($initiative->person->contact_numbers as $contactNumber)
                    <div class="flex gap-1">
                        <a  
                            class="text-brandColor"
                            href="callto:{{ $contactNumber['value'] }}"
                        >
                            {{ $contactNumber['value'] }}
                        </a>

                        <span class="text-gray-500 dark:text-gray-300">
                            ({{ $contactNumber['label'] }})
                        </span>
                    </div>
                @endforeach

                {!! view_render_event('admin.initiatives.view.person.contact_numbers.after', ['initiative' => $initiative]) !!}
            </div>
        </div>
    </div>
@endif
{!! view_render_event('admin.initiatives.view.person.after', ['initiative' => $initiative]) !!}