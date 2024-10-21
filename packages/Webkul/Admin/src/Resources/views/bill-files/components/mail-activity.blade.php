@props([
    'entity'            => null,
    'entityControlName' => null,
])

<v-bill-mail-activity
    :entity="{{ json_encode($entity) }}"
    entity-control-name="{{ $entityControlName }}"
>
    <button
        type="button"
        class="flex h-[74px] w-[84px] flex-col items-center justify-center gap-1 rounded-lg border border-transparent bg-green-200 font-medium text-green-900 transition-all hover:border-green-400"
    >
        <span class="icon-mail text-2xl dark:!text-green-900"></span>

        @lang('admin::app.components.activities.actions.mail.btn')
    </button>
</v-bill-mail-activity>

@pushOnce('scripts')
    <script type="text/x-template" id="v-bill-mail-activity-template">
        <div>
            <button
                type="button"
                @click="openModal"
                v-bind="$attrs"
            >
                <slot></slot>
            </button>

            <Teleport to="body">
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="mailActionForm"
                >
                    <form @submit="handleSubmit($event, save)">
                        <x-admin::modal ref="billMailModal">
                            <x-slot:header>
                                <h3 class="text-lg font-bold">
                                    @lang('admin::app.components.activities.actions.mail.add-email-record')
                                </h3>
                            </x-slot>

                            <x-slot:content>
                                <!-- Activity Type -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="type"
                                    value="email"
                                />

                                <!-- Id -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    ::name="entityControlName"
                                    ::value="entity.id"
                                />

                                <!-- Subject -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.components.activities.actions.mail.subject')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="title"
                                        rules="required"
                                        :label="trans('admin::app.components.activities.actions.mail.subject')"
                                        :placeholder="trans('admin::app.components.activities.actions.mail.subject')"
                                    />

                                    <x-admin::form.control-group.error control-name="title" />
                                </x-admin::form.control-group>

                                <!-- Content -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.components.activities.actions.mail.content')
                                    </x-admin::form.control-group.label>

                                    <div
                                        ref="emailContent"
                                        contenteditable="true"
                                        class="w-full min-h-[150px] p-2 border rounded-md overflow-auto"
                                        @paste.prevent="handlePaste"
                                    ></div>

                                    <input type="hidden" name="comment" :value="emailContent">

                                    <x-admin::form.control-group.error control-name="comment" />
                                </x-admin::form.control-group>

                                <!-- Participants -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.components.activities.actions.mail.participants')
                                    </x-admin::form.control-group.label>

                                    <x-admin::activities.actions.activity.participants />
                                </x-admin::form.control-group>

                                <!-- Date Sent -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.components.activities.actions.mail.date-sent')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="date"
                                        name="schedule_from"
                                        rules="required"
                                        :label="trans('admin::app.components.activities.actions.mail.date-sent')"
                                    />

                                    <x-admin::form.control-group.error control-name="schedule_from" />
                                </x-admin::form.control-group>
                            </x-slot>

                            <x-slot:footer>
                                <button
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('admin::app.components.activities.actions.mail.save-btn')
                                </button>
                            </x-slot>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </Teleport>
        </div>
    </script>

    <script type="module">
        app.component('v-bill-mail-activity', {
            template: '#v-bill-mail-activity-template',

            props: ['entity', 'entityControlName'],

            data() {
                return {
                    isLoading: false,
                    emailContent: '',
                };
            },

            methods: {
                openModal() {
                    this.$refs.billMailModal.open();
                },

                handlePaste(e) {
                    e.preventDefault();
                    const text = e.clipboardData.getData('text/html') || e.clipboardData.getData('text/plain');
                    document.execCommand('insertHTML', false, text);
                    this.emailContent = this.$refs.emailContent.innerHTML;
                },

                save(params) {
                    this.isLoading = true;

                    let data = Object.assign({}, params, {
                        bill_file_id: this.entity.id,
                        comment: this.emailContent
                    });

                    this.$axios.post("{{ route('admin.bill-files.mail-activity.store') }}", data)
                        .then(response => {
                            this.isLoading = false;
                            this.$refs.billMailModal.close();
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                            this.$emitter.emit('on-activity-added', response.data.data);
                            this.$refs.emailContent.innerHTML = '';
                            this.emailContent = '';
                        })
                        .catch(error => {
                            this.isLoading = false;
                            if (error.response && error.response.status === 422) {
                                this.$refs.mailActionForm.setErrors(error.response.data.errors);
                            } else {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message || 'An error occurred' });
                                this.$refs.billMailModal.close();
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
