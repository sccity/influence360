@props([
    'entity'            => null,
    'entityControlName' => null,
])

<!-- Note Button -->
<div>
    {!! view_render_event('admin.components.activities.actions.note.create_btn.before') !!}

    <button
        class="flex h-[74px] w-[84px] flex-col items-center justify-center gap-1 rounded-lg border border-transparent bg-orange-200 font-medium text-orange-800 transition-all hover:border-orange-400"
        @click="$refs.noteActionComponent.openModal('mail')"
    >
        <span class="icon-note text-2xl dark:!text-orange-800"></span>

        @lang('admin::app.components.activities.actions.note.btn')
    </button>

    {!! view_render_event('admin.components.activities.actions.note.create_btn.after') !!}

    {!! view_render_event('admin.components.activities.actions.note.before') !!}

    <!-- Note Action Vue Component -->
    <v-note-activity
        ref="noteActionComponent"
        :entity="{{ json_encode($entity) }}"
        entity-control-name="{{ $entityControlName }}"
    ></v-note-activity>

    {!! view_render_event('admin.components.activities.actions.note.after') !!}
</div>

@pushOnce('scripts')
    <script type="text/x-template" id="v-note-activity-template">
        <Teleport to="body">
            {!! view_render_event('admin.components.activities.actions.note.form_controls.before') !!}

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form @submit="handleSubmit($event, save)">
                    {!! view_render_event('admin.components.activities.actions.note.form_controls.modal.before') !!}

                    <x-admin::modal 
                        ref="noteActivityModal"
                        position="bottom-right"
                    >
                        <x-slot:header>
                            {!! view_render_event('admin.components.activities.actions.note.form_controls.modal.header.title.before') !!}

                            <h3 class="text-base font-semibold dark:text-white">
                                @lang('admin::app.components.activities.actions.note.title')
                            </h3>

                            {!! view_render_event('admin.components.activities.actions.note.form_controls.modal.header.title.after') !!}
                        </x-slot>

                        <x-slot:content>
                            {!! view_render_event('admin.components.activities.actions.note.form_controls.modal.header.content.controls.before') !!}

                            <!-- Activity Type -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="type"
                                value="note"
                            />
                            
                            <!-- Id -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                ::name="entityControlName"
                                ::value="entity.id"
                            />

                            <!-- Comment -->
                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.components.activities.actions.note.comment')
                                </x-admin::form.control-group.label>

                                <div
                                    ref="noteContent"
                                    contenteditable="true"
                                    class="w-full min-h-[150px] p-2 border rounded-md overflow-auto"
                                    @paste.prevent="handlePaste"
                                ></div>

                                <input type="hidden" name="comment" :value="noteContent">

                                <x-admin::form.control-group.error control-name="comment" />
                            </x-admin::form.control-group>

                            {!! view_render_event('admin.components.activities.actions.note.form_controls.modal.header.content.controls.after') !!}
                        </x-slot>

                        <x-slot:footer>
                            {!! view_render_event('admin.components.activities.actions.note.form_controls.modal.header.footer.save_button.before') !!}

                            <x-admin::button
                                class="primary-button"
                                :title="trans('admin::app.components.activities.actions.note.save-btn')"
                                ::loading="isStoring"
                                ::disabled="isStoring"
                            />

                            {!! view_render_event('admin.components.activities.actions.note.form_controls.modal.header.footer.save_button.after') !!}
                        </x-slot>
                    </x-admin::modal>

                    {!! view_render_event('admin.components.activities.actions.note.form_controls.modal.after') !!}
                </form>
            </x-admin::form>

            {!! view_render_event('admin.components.activities.actions.note.form_controls.after') !!}
        </Teleport>
    </script>

    <script type="module">
        app.component('v-note-activity', {
            template: '#v-note-activity-template',

            props: {
                entity: {
                    type: Object,
                    required: true,
                    default: () => {}
                },

                entityControlName: {
                    type: String,
                    required: true,
                    default: ''
                }
            },

            data: function () {
                return {
                    isStoring: false,
                    noteContent: '',
                }
            },

            methods: {
                openModal(type) {
                    this.$refs.noteActivityModal.open();
                },

                handlePaste(e) {
                    e.preventDefault();
                    const text = e.clipboardData.getData('text/html') || e.clipboardData.getData('text/plain');
                    document.execCommand('insertHTML', false, text);
                    this.updateNoteContent();
                },

                updateNoteContent() {
                    this.noteContent = this.$refs.noteContent.innerHTML;
                },

                save(params) {
                    this.isStoring = true;

                    // Update noteContent one last time before submission
                    this.updateNoteContent();

                    params.comment = this.noteContent;

                    this.$axios.post("{{ route('admin.activities.store') }}", params)
                        .then (response => {
                            this.isStoring = false;

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.$emitter.emit('on-activity-added', response.data.data);

                            this.$refs.noteActivityModal.close();

                            // Clear the content after successful submission
                            this.$refs.noteContent.innerHTML = '';
                            this.noteContent = '';
                        })
                        .catch (error => {
                            this.isStoring = false;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            } else {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                                this.$refs.noteActivityModal.close();
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce
