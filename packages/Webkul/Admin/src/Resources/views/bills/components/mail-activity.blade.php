@props([
    'entity'            => null,
    'entityControlName' => null,
])

<div>
    <button
        class="flex h-[74px] w-[84px] flex-col items-center justify-center gap-1 rounded-lg border border-transparent bg-green-200 font-medium text-green-900 transition-all hover:border-green-400"
        @click="$refs.mailActionComponent.openModal()"
    >
        <span class="icon-mail text-2xl dark:!text-green-900"></span>

        @lang('admin::app.components.activities.actions.mail.btn')
    </button>

    <v-bill-mail-activity
        ref="mailActionComponent"
        :entity="{{ json_encode($entity) }}"
        entity-control-name="{{ $entityControlName }}"
    ></v-bill-mail-activity>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-bill-mail-activity-template">
        <div>
            <x-admin::modal ref="billMailModal">
                <x-slot:header>
                    <p class="text-lg text-gray-800 dark:text-white font-bold">
                        @lang('admin::app.components.activities.actions.mail.title')
                    </p>
                </x-slot:header>

                <x-slot:content>
                    <x-admin::form
                        :action="route('admin.bills.mail-activity.store')"
                        method="POST"
                        ref="mailActionForm"
                    >
                        <!-- Add your form fields here -->
                    </x-admin::form>
                </x-slot:content>
            </x-admin::modal>
        </div>
    </script>

    <script type="module">
        app.component('v-bill-mail-activity', {
            template: '#v-bill-mail-activity-template',

            props: ['entity', 'entityControlName'],

            data() {
                return {
                    isLoading: false,
                }
            },

            methods: {
                openModal() {
                    this.$refs.billMailModal.open();
                },

                sendMail() {
                    this.$refs.mailActionForm.submit();
                },
            },
        });
    </script>
@endPushOnce

