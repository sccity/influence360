<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.contacts.persons.edit.title')
    </x-slot>

    {!! view_render_event('admin.persons.edit.form.before') !!}

    <x-admin::form
        :action="route('admin.contacts.persons.update', $person->id)"
        method="PUT"
        enctype="multipart/form-data"
    >
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <div class="flex cursor-pointer items-center">
                        {!! view_render_event('admin.persons.edit.breadcrumbs.before') !!}

                        <x-admin::breadcrumbs 
                            name="contacts.persons.edit" 
                            :entity="$person"
                        />

                        {!! view_render_event('admin.persons.edit.breadcrumbs.after') !!}
                    </div>

                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.contacts.persons.edit.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <!--  Save button for Person -->
                    <div class="flex items-center gap-x-2.5">
                        {!! view_render_event('admin.persons.edit.save_button.before') !!}

                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('admin::app.contacts.persons.edit.save-btn')
                        </button>

                        {!! view_render_event('admin.persons.edit.save_button.after') !!}
                    </div>
                </div>
            </div>

            <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                {!! view_render_event('admin.contacts.persons.edit.form_controls.before') !!}

                <x-admin::attributes
                    :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                        'entity_type' => 'persons',
                    ])"
                    :entity="$person"
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
                                :value="$person->street"
                                :label="__('admin::app.contacts.persons.edit.street')"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>City</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="city"
                                :value="$person->city"
                                :label="__('admin::app.contacts.persons.edit.city')"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>State</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="state"
                                :value="$person->state"
                                :label="__('admin::app.contacts.persons.edit.state')"
                            />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>ZIP</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control
                                type="text"
                                name="zip"
                                :value="$person->zip"
                                :label="__('admin::app.contacts.persons.edit.zip')"
                            />
                        </x-admin::form.control-group>
                    </div>
                </div>

                <x-admin::form.control-group>
                    <x-admin::form.control-group.label>Emails</x-admin::form.control-group.label>
                    
                    <x-admin::attributes.edit.email
                        :attribute='["code" => "emails", "name" => "Emails"]'
                        :value='$person->emails'
                    />
                </x-admin::form.control-group>
                
                <v-inline-address-edit
                    :attribute='@json(["code" => "address", "name" => "Address"])'
                    :value='@json($person->address)'
                ></v-inline-address-edit>
                
                {!! view_render_event('admin.contacts.persons.edit.form_controls.after') !!}
            </div>
        </div>
    </x-admin::form>

    {!! view_render_event('admin.persons.edit.form.after') !!}
</x-admin::layouts>
