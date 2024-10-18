<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.bills.edit.title')
    </x-slot>

    {!! view_render_event('admin.bills.edit.before', ['bill' => $bill]) !!}

    <v-bill-edit></v-bill-edit>

    {!! view_render_event('admin.bills.edit.after', ['bill' => $bill]) !!}
</x-admin::layouts>

@pushOnce('scripts')
    <script type="text/x-template" id="v-bill-edit-template">
        <div>
            <x-admin::form :action="route('admin.bills.update', $bill->id)" method="PUT">
                {!! view_render_event('admin.bills.edit.form_controls.before', ['bill' => $bill]) !!}

                <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
                    <p class="text-xl text-gray-800 dark:text-white font-bold">
                        @lang('admin::app.bills.edit.title')
                    </p>

                    <div class="flex gap-x-2.5 items-center">
                        <button 
                            type="submit"
                            class="primary-button"
                        >
                            @lang('admin::app.bills.edit.save-btn')
                        </button>
                    </div>
                </div>

                <!-- Add your form fields here -->

                {!! view_render_event('admin.bills.edit.form_controls.after', ['bill' => $bill]) !!}
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-bill-edit', {
            template: '#v-bill-edit-template',

            data() {
                return {
                    bill: @json($bill)
                }
            },

            methods: {
                // Add your methods here
            }
        });
    </script>
@endPushOnce

