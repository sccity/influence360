<x-admin::layouts>
    <!--Page title -->
    <x-slot:title>
        @lang('admin::app.contacts.persons.create.title')
    </x-slot>

    {!! view_render_event('admin.persons.create.form.before') !!}
    
    <!--Create Page Form -->
    <x-admin::form
        :action="route('admin.contacts.persons.store')"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="flex flex-col gap-4">
            <!-- Header -->
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <div class="flex cursor-pointer items-center">
                        {!! view_render_event('admin.persons.create.breadcrumbs.before') !!}

                        <!-- Breadcrumb -->
                        <x-admin::breadcrumbs name="contacts.persons.create" />

                        {!! view_render_event('admin.persons.create.breadcrumbs.after') !!}
                    </div>

                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.contacts.persons.create.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <div class="flex items-center gap-x-2.5">
                        {!! view_render_event('admin.persons.create.create_button.before') !!}

                        <!-- Create button for Person -->
                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('admin::app.contacts.persons.create.save-btn')
                        </button>

                        {!! view_render_event('admin.persons.create.create_button.after') !!}
                    </div>
                </div>
            </div>
            
            <!-- Form fields -->
            <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                {!! view_render_event('admin.persons.create.form_controls.before') !!}

                <x-admin::attributes
                    :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                        'entity_type' => 'persons',
                    ])"
                />

                <!-- Address Fields -->
                <div class="mt-4">
                    <h5 class="font-semibold dark:text-white mb-2">Address</h5>
                    <div class="grid grid-cols-2 gap-4">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>Street</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="street"
                                :label="__('admin::app.contacts.persons.create.street')"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>City</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="city"
                                :label="__('admin::app.contacts.persons.create.city')"
                            />
                        </x-admin::form.control-group>

                        <!-- State field -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.contacts.persons.create.state')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="state"
                                :placeholder="trans('admin::app.contacts.persons.create.select-state')"
                            >
                                <option value="">@lang('admin::app.contacts.persons.create.select-state')</option>
                                
                                @foreach ($states as $abbr => $stateName)
                                    <option value="{{ $abbr }}">
                                        {{ $stateName }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="state"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>ZIP</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="zip"
                                :label="__('admin::app.contacts.persons.create.zip')"
                            />
                        </x-admin::form.control-group>

                        <!-- Organization field -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.contacts.persons.create.organization')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="select"
                                name="organization_id"
                                :placeholder="trans('admin::app.contacts.persons.create.select-organization')"
                            >
                                <option value="">@lang('admin::app.contacts.persons.create.select-organization')</option>
                                
                                @foreach ($organizations as $organization)
                                    <option value="{{ $organization->id }}">
                                        {{ $organization->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>

                            <x-admin::form.control-group.error
                                control-name="organization_id"
                            />
                        </x-admin::form.control-group>
                    </div>
                </div>

                {!! view_render_event('admin.persons.create.form_controls.after') !!}
            </div>
        </div>
    </x-admin::form>

    {!! view_render_event('admin.persons.create.form.after') !!}
</x-admin::layouts>
