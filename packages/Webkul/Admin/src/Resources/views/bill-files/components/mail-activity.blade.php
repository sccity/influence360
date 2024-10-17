@props([
    'entity'            => null,
    'entityControlName' => null,
])

<div>
    <button
        class="flex h-[74px] w-[84px] flex-col items-center justify-center gap-1 rounded-lg border border-transparent bg-green-200 font-medium text-green-900 transition-all hover:border-green-400"
        @click="$refs.billFileMailActionComponent.openModal()"
    >
        <span class="icon-mail text-2xl dark:!text-green-900"></span>

        @lang('admin::app.components.activities.actions.mail.btn')
    </button>

    <v-bill-file-mail-activity
        ref="billFileMailActionComponent"
        :entity="{{ json_encode($entity) }}"
        entity-control-name="{{ $entityControlName }}"
    ></v-bill-file-mail-activity>
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-bill-file-mail-activity-template">
        <Teleport to="body">
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form @submit="handleSubmit($event, save)">
                    <x-admin::modal ref="billFileMailModal">
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
                                    name="subject"
                                    rules="required"
                                    :label="trans('admin::app.components.activities.actions.mail.subject')"
                                    :placeholder="trans('admin::app.components.activities.actions.mail.subject')"
                                />

                                <x-admin::form.control-group.error control-name="subject" />
                            </x-admin::form.control-group>

                            <!-- Content -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.components.activities.actions.mail.content')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="content"
                                    rules="required"
                                    :label="trans('admin::app.components.activities.actions.mail.content')"
                                />

                                <x-admin::form.control-group.error control-name="content" />
                            </x-admin::form.control-group>

                            <!-- Participants (Persons only) -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.components.activities.actions.mail.participants')
                                </x-admin::form.control-group.label>

                                <v-bill-file-participants
                                    name="participants"
                                    :errors="errors"
                                ></v-bill-file-participants>
                            </x-admin::form.control-group>

                            <!-- Date Sent -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.components.activities.actions.mail.date-sent')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="date"
                                    name="date_sent"
                                    :value="date('Y-m-d')"
                                    rules="required"
                                    :label="trans('admin::app.components.activities.actions.mail.date-sent')"
                                />

                                <x-admin::form.control-group.error control-name="date_sent" />
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
    </script>

    <script type="module">
        app.component('v-bill-file-mail-activity', {
            template: '#v-bill-file-mail-activity-template',

            props: ['entity', 'entityControlName'],

            data() {
                return {
                    isLoading: false,
                };
            },

            methods: {
                openModal() {
                    this.$refs.billFileMailModal.open();
                },

                save(params) {
                    this.isLoading = true;

                    this.$axios.post("{{ route('admin.bill-files.activities.store', ['id' => $entity->id]) }}", params)
                        .then(response => {
                            this.isLoading = false;

                            this.$refs.billFileMailModal.close();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.$emitter.emit('on-activity-added', response.data.data);
                        })
                        .catch(error => {
                            this.isLoading = false;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
