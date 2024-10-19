<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.initiatives.create.title')
    </x-slot>

    {!! view_render_event('admin.initiatives.create.form.before') !!}

    <!-- Create Initiative Form -->
    <x-admin::form :action="route('admin.initiatives.store')">
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <div class="flex cursor-pointer items-center">
                        <x-admin::breadcrumbs name="initiatives.create" />
                    </div>

                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.initiatives.create.title')
                    </div>
                </div>

                {!! view_render_event('admin.initiatives.create.save_button.before') !!}

                <div class="flex items-center gap-x-2.5">
                    <!-- Save button for person -->
                    <div class="flex items-center gap-x-2.5">
                        {!! view_render_event('admin.initiatives.create.form_buttons.before') !!}

                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('admin::app.initiatives.create.save-btn')
                        </button>

                        {!! view_render_event('admin.initiatives.create.form_buttons.after') !!}
                    </div>
                </div>

                {!! view_render_event('admin.initiatives.create.save_button.after') !!}
            </div>

            @if (request('stage_id'))
                <input
                    type="hidden"
                    id="initiative_pipeline_stage_id"
                    name="initiative_pipeline_stage_id"
                    value="{{ request('stage_id') }}"
                />
            @endif

            <!-- Initiative Create Component -->
            <v-initiative-create></v-initiative-create>
        </div>
    </x-admin::form>

    {!! view_render_event('admin.initiatives.create.form.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-initiative-create-template"
        >
            <div class="box-shadow flex flex-col gap-4 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 max-xl:flex-wrap">
                {!! view_render_event('admin.initiatives.edit.form_controls.before') !!}

                <div class="flex gap-2 border-b border-gray-200 dark:border-gray-800">
                    <!-- Tabs -->
                    <template v-for="tab in tabs" :key="tab.id">
                        {!! view_render_event('admin.initiatives.create.tabs.before') !!}

                        <a
                            :href="'#' + tab.id"
                            :class="[
                                'inline-block px-3 py-2.5 border-b-2  text-sm font-medium ',
                                activeTab === tab.id
                                ? 'text-brandColor border-brandColor dark:brandColor dark:brandColor'
                                : 'text-gray-600 dark:text-gray-300  border-transparent hover:text-gray-800 hover:border-gray-400 dark:hover:border-gray-400  dark:hover:text-white'
                            ]"
                            @click="scrollToSection(tab.id)"
                            :text="tab.label"
                        ></a>

                        {!! view_render_event('admin.initiatives.create.tabs.after') !!}
                    </template>
                </div>

                <div class="flex flex-col gap-4 px-4 py-2">
                    {!! view_render_event('admin.initiatives.create.details.before') !!}

                    <!-- Details section -->
                    <div
                        class="flex flex-col gap-4"
                        id="initiative-details"
                    >
                        <div class="flex flex-col gap-1">
                            <p class="text-base font-semibold dark:text-white">
                                @lang('admin::app.initiatives.create.details')
                            </p>

                            <p class="text-gray-600 dark:text-white">
                                @lang('admin::app.initiatives.create.details-info')
                            </p>
                        </div>

                        <div class="w-1/2">
                            {!! view_render_event('admin.initiatives.create.details.attributes.before') !!}

                            <!-- Initiative Details Title and Description -->
                            <x-admin::attributes
                                :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                    ['code', 'NOTIN', ['initiative_value', 'initiative_type_id', 'initiative_source_id', 'expected_close_date', 'user_id', 'initiative_pipeline_id', 'initiative_pipeline_stage_id']],
                                    'entity_type' => 'initiatives',
                                    'quick_add'   => 1
                                ])"
                                :custom-validations="[
                                    'expected_close_date' => [
                                        'date_format:yyyy-MM-dd',
                                        'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                    ],
                                ]"
                            />

                            <!-- Initiative Details Oter input fields -->
                            <div class="flex gap-4 max-sm:flex-wrap">
                                <div class="w-full">
                                    <x-admin::attributes
                                        :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                            ['code', 'IN', ['initiative_value', 'initiative_type_id', 'initiative_source_id']],
                                            'entity_type' => 'initiatives',
                                            'quick_add'   => 1
                                        ])"
                                        :custom-validations="[
                                            'expected_close_date' => [
                                                'date_format:yyyy-MM-dd',
                                                'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                            ],
                                        ]"
                                    />
                                </div>

                                <div class="w-full">
                                    <x-admin::attributes
                                        :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                            ['code', 'IN', ['expected_close_date', 'user_id']],
                                            'entity_type' => 'initiatives',
                                            'quick_add'   => 1
                                        ])"
                                        :custom-validations="[
                                            'expected_close_date' => [
                                                'date_format:yyyy-MM-dd',
                                                'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                            ],
                                        ]"
                                    />
                                </div>
                            </div>

                            {!! view_render_event('admin.initiatives.create.details.attributes.after') !!}
                        </div>
                    </div>

                    {!! view_render_event('admin.initiatives.create.details.after') !!}

                    {!! view_render_event('admin.initiatives.create.contact_person.before') !!}

                    <!-- Contact Person -->
                    <div
                        class="flex flex-col gap-4"
                        id="contact-person"
                    >
                        <div class="flex flex-col gap-1">
                            <p class="text-base font-semibold dark:text-white">
                                @lang('admin::app.initiatives.create.contact-person')
                            </p>

                            <p class="text-gray-600 dark:text-white">
                                @lang('admin::app.initiatives.create.contact-info')
                            </p>
                        </div>

                        <div class="w-1/2">
                            <!-- Contact Person Component -->
                            @include('admin::initiatives.common.contact')
                        </div>
                    </div>

                    {!! view_render_event('admin.initiatives.create.contact_person.after') !!}
                </div>

                {!! view_render_event('admin.initiatives.form_controls.after') !!}
            </div>
        </script>

        <script type="module">
            app.component('v-initiative-create', {
                template: '#v-initiative-create-template',

                data() {
                    return {
                        activeTab: 'initiative-details',

                        tabs: [
                            { id: 'initiative-details', label: '@lang('admin::app.initiatives.create.details')' },
                            { id: 'contact-person', label: '@lang('admin::app.initiatives.create.contact-person')' }
                        ],
                    };
                },

                methods: {
                    /**
                     * Scroll to the section.
                     *
                     * @param {String} tabId
                     *
                     * @returns {void}
                     */
                    scrollToSection(tabId) {
                        const section = document.getElementById(tabId);

                        if (section) {
                            section.scrollIntoView({ behavior: 'smooth' });
                        }
                    },
                },
            });
        </script>
    @endPushOnce

    @pushOnce('styles')
        <style>
            html {
                scroll-behavior: smooth;
            }
        </style>
    @endPushOnce
</x-admin::layouts>
