<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.bills.create.title')
    </x-slot>

    {!! view_render_event('admin.bills.create.before') !!}

    <v-bill-create></v-bill-create>

    {!! view_render_event('admin.bills.create.after') !!}
</x-admin::layouts>

@pushOnce('scripts')
    <script type="text/x-template" id="v-bill-create-template">
        <div>
            <x-admin::form :action="route('admin.bills.store')">
                {!! view_render_event('admin.bills.create.form_controls.before') !!}

                <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
                    <p class="text-xl text-gray-800 dark:text-white font-bold">
                        @lang('admin::app.bills.create.title')
                    </p>

                    <div class="flex gap-x-2.5 items-center">
                        <button 
                            type="submit"
                            class="primary-button"
                        >
                            @lang('admin::app.bills.create.save-btn')
                        </button>
                    </div>
                </div>

                <!-- Add your form fields here -->

                {!! view_render_event('admin.bills.create.form_controls.after') !!}
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-bill-create', {
            template: '#v-bill-create-template',

            data() {
                return {
                    // Add your data properties here
                }
            },

            methods: {
                // Add your methods here
            }
        });
    </script>
@endPushOnce

