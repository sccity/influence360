<!-- Stages Navigation -->
{!! view_render_event('admin.initiatives.view.stages.before', ['initiative' => $initiative]) !!}

<!-- Stages Vue Component -->
<v-initiative-stages>
    <x-admin::shimmer.initiatives.view.stages :count="$initiative->pipeline->stages->count() - 1" />
</v-initiative-stages>

{!! view_render_event('admin.initiatives.view.stages.after', ['initiative' => $initiative]) !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-initiative-stages-template">
        <!-- Stages Container -->
        <div
            class="flex"
            :class="{'opacity-50 pointer-events-none': isUpdating}"
        >
            <!-- Stages Item -->
            <template v-for="stage in stages">
                {!! view_render_event('admin.initiatives.view.stages.items.before', ['initiative' => $initiative]) !!}

                <div
                    class="stage relative flex h-7 min-w-24 cursor-pointer items-center justify-center bg-white pl-7 pr-4 dark:bg-gray-900 ltr:first:rounded-l-lg rtl:first:rounded-r-lg"
                    :class="{
                        '!bg-green-500 text-white dark:text-gray-900 ltr:after:bg-green-500 rtl:before:bg-green-500': currentStage.sort_order >= stage.sort_order,
                        '!bg-red-500 text-white dark:text-gray-900 ltr:after:bg-red-500 rtl:before:bg-red-500': currentStage.code == 'lost',
                    }"
                    v-if="! ['won', 'lost'].includes(stage.code)"
                    @click="update(stage)"
                >
                    <span class="z-20 whitespace-nowrap text-sm font-medium dark:text-white">
                        @{{ stage.name }}
                    </span>
                </div>

                {!! view_render_event('admin.initiatives.view.stages.items.after', ['initiative' => $initiative]) !!}
            </template>

            {!! view_render_event('admin.initiatives.view.stages.items.dropdown.before', ['initiative' => $initiative]) !!}

            <!-- Won/Lost Stage Item -->
            <x-admin::dropdown position="bottom-right">
                <x-slot:toggle>
                    {!! view_render_event('admin.initiatives.view.stages.items.dropdown.toggle.before', ['initiative' => $initiative]) !!}

                    <div
                        class="relative flex h-7 min-w-24 cursor-pointer items-center justify-center rounded-r-lg bg-white pl-7 pr-4 dark:bg-gray-900"
                        :class="{
                            '!bg-green-500 text-white dark:text-gray-900 after:bg-green-500': ['won', 'lost'].includes(currentStage.code) && currentStage.code == 'won',
                            '!bg-red-500 text-white dark:text-gray-900 after:bg-red-500': ['won', 'lost'].includes(currentStage.code) && currentStage.code == 'lost',
                        }"
                    >
                        <span class="z-20 whitespace-nowrap text-sm font-medium dark:text-white">
                            {{ __('admin::app.initiatives.view.stages.won-lost') }}
                        </span>

                        <span class="icon-down-arrow text-2xl dark:text-gray-900"></span>
                    </div>

                    {!! view_render_event('admin.initiatives.view.stages.items.dropdown.toggle.after', ['initiative' => $initiative]) !!}
                </x-slot>

                <x-slot:menu>
                    {!! view_render_event('admin.initiatives.view.stages.items.dropdown.menu_item.before', ['initiative' => $initiative]) !!}

                    <x-admin::dropdown.menu.item
                        @click="openModal(this.stages.find(stage => stage.code == 'won'))"
                    >
                        @lang('admin::app.initiatives.view.stages.won')
                    </x-admin::dropdown.menu.item>

                    <x-admin::dropdown.menu.item
                        @click="openModal(this.stages.find(stage => stage.code == 'lost'))"
                    >
                        @lang('admin::app.initiatives.view.stages.lost')
                    </x-admin::dropdown.menu.item>

                    {!! view_render_event('admin.initiatives.view.stages.items.dropdown.menu_item.after', ['initiative' => $initiative]) !!}
                </x-slot>
            </x-admin::dropdown>

            {!! view_render_event('admin.initiatives.view.stages.items.dropdown.after', ['initiative' => $initiative]) !!}

            {!! view_render_event('admin.initiatives.view.stages.form_controls.before', ['initiative' => $initiative]) !!}

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="stageUpdateForm"
            >
                <form @submit="handleSubmit($event, handleFormSubmit)">
                    {!! view_render_event('admin.initiatives.view.stages.form_controls.modal.before', ['initiative' => $initiative]) !!}

                    <x-admin::modal ref="stageUpdateModal">
                        <x-slot:header>
                            {!! view_render_event('admin.initiatives.view.stages.form_controls.modal.header.before', ['initiative' => $initiative]) !!}

                            <h3 class="text-base font-semibold dark:text-white">
                                @lang('admin::app.initiatives.view.stages.need-more-info')
                            </h3>

                            {!! view_render_event('admin.initiatives.view.stages.form_controls.modal.header.after', ['initiative' => $initiative]) !!}
                        </x-slot>

                        <x-slot:content>
                            {!! view_render_event('admin.initiatives.view.stages.form_controls.modal.content.before', ['initiative' => $initiative]) !!}

                            <!-- Won Value -->
                            <template v-if="nextStage.code == 'won'">
                            </template>

                            <!-- Lost Reason -->
                            <template v-else>
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.initiatives.view.stages.lost-reason')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="textarea"
                                        name="lost_reason"
                                        v-model="nextStage.lost_reason"
                                    />
                                </x-admin::form.control-group>
                            </template>

                            <!-- Closed At -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.initiatives.view.stages.closed-at')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="date"
                                    name="closed_at"
                                    v-model="nextStage.closed_at"
                                    :label="trans('admin::app.initiatives.view.stages.closed-at')"
                                />

                                <x-admin::form.control-group.error control-name="closed_at"/>
                            </x-admin::form.control-group>

                            {!! view_render_event('admin.initiatives.view.stages.form_controls.modal.content.after', ['initiative' => $initiative]) !!}
                        </x-slot>

                        <x-slot:footer>
                            {!! view_render_event('admin.initiatives.view.stages.form_controls.modal.footer.before', ['initiative' => $initiative]) !!}

                            <button
                                type="submit"
                                class="primary-button"
                            >
                                @lang('admin::app.initiatives.view.stages.save-btn')
                            </button>

                            {!! view_render_event('admin.initiatives.view.stages.form_controls.modal.footer.after', ['initiative' => $initiative]) !!}
                        </x-slot>
                    </x-admin::modal>

                    {!! view_render_event('admin.initiatives.view.stages.form_controls.modal.after', ['initiative' => $initiative]) !!}
                </form>
            </x-admin::form>

            {!! view_render_event('admin.initiatives.view.stages.form_controls.after', ['initiative' => $initiative]) !!}
        </div>
    </script>

    <script type="module">
        app.component('v-initiative-stages', {
            template: '#v-initiative-stages-template',

            data() {
                return {
                    isUpdating: false,

                    currentStage: @json($initiative->stage),

                    nextStage: null,

                    stages: @json($initiative->pipeline->stages),
                }
            },

            methods: {
                openModal(stage) {
                    if (this.currentStage.code == stage.code) {
                        return;
                    }

                    this.nextStage = stage;

                    this.$refs.stageUpdateModal.open();
                },

                handleFormSubmit(event) {
                    let params = {
                        'initiative_pipeline_stage_id': this.nextStage.id
                    };

                    if (this.nextStage.code == 'won') {
                        params.initiative_value = this.nextStage.initiative_value;

                        params.closed_at = this.nextStage.closed_at;
                    } else if (this.nextStage.code == 'lost') {
                        params.lost_reason = this.nextStage.lost_reason;

                        params.closed_at = this.nextStage.closed_at;
                    }

                    this.update(this.nextStage, params);
                },

                update(stage, params = null) {
                    if (this.currentStage.code == stage.code) {
                        return;
                    }

                    this.$refs.stageUpdateModal.close();

                    this.isUpdating = true;

                    let self = this;

                    this.$axios
                        .put("{{ route('admin.initiatives.stage.update', $initiative->id) }}", params ?? {
                            'initiative_pipeline_stage_id': stage.id
                        })
                        .then (function(response) {
                            self.isUpdating = false;

                            self.currentStage = stage;

                            self.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch (function (error) {
                            self.isUpdating = false;

                            self.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },
            }
        });
    </script>
@endPushOnce
