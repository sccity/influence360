{!! view_render_event('admin.initiatives.index.kanban.before') !!}

<!-- Kanabn Vue Component -->
<v-initiatives-kanban>
    <div class="flex flex-col gap-4">
        <!-- Shimmer -->
        <x-admin::shimmer.initiatives.index.kanban />
    </div>
</v-initiatives-kanban>

{!! view_render_event('admin.initiatives.index.kanban.after') !!}

@pushOnce('scripts')
    <script type="text/x-template" id="v-initiatives-kanban-template">
        <template v-if="isLoading">
            <div class="flex flex-col gap-4">
                <x-admin::shimmer.initiatives.index.kanban />
            </div>
        </template>

        <template v-else>
            <div class="flex flex-col gap-4">
                @include('admin::initiatives.index.kanban.toolbar')

                {!! view_render_event('admin.initiatives.index.kanban.content.before') !!}

                <div class="flex gap-2.5 overflow-x-auto">
                    <!-- Stage Cards -->
                    <div
                        class="flex min-w-[275px] max-w-[275px] flex-col gap-1 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900"
                        v-for="(stage, index) in stageInitiatives"
                    >
                        {!! view_render_event('admin.initiatives.index.kanban.content.stage.header.before') !!}

                        <!-- Stage Header -->
                        <div class="flex flex-col px-2 py-3">
                            <!-- Stage Title and Action -->
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-medium dark:text-white">
                                    @{{ stage.name }} (@{{ stage.initiatives.meta.total }})
                                </span>

                                @if (bouncer()->hasPermission('initiatives.create'))
                                    <a
                                        :href="'{{ route('admin.initiatives.create') }}' + '?stage_id=' + stage.id"
                                        class="icon-add cursor-pointer rounded p-1 text-lg text-gray-600 transition-all hover:bg-gray-200 hover:text-gray-800 dark:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-white"
                                        target="_blank"
                                    >
                                    </a>
                                @endif
                            </div>
                        </div>

                        {!! view_render_event('admin.initiatives.index.kanban.content.stage.header.after') !!}

                        {!! view_render_event('admin.initiatives.index.kanban.content.stage.body.before') !!}

                        <!-- Draggable Stage Initiative Cards -->
                        <draggable
                            class="flex h-[calc(100vh-317px)] flex-col gap-2 overflow-y-auto p-2"
                            :class="{ 'justify-center': stage.initiatives.data.length === 0 }"
                            ghost-class="draggable-ghost"
                            handle=".initiative-item"
                            v-bind="{animation: 200}"
                            :list="stage.initiatives.data"
                            item-key="id"
                            group="initiatives"
                            @scroll="handleScroll(stage, $event)"
                            @change="updateStage(stage, $event)"
                        >
                            <template #header>
                                <div
                                    class="flex flex-col items-center justify-center"
                                    v-if="! stage.initiatives.data.length"
                                >
                                    <img
                                        class="dark:mix-blend-exclusion dark:invert"
                                        src="{{ vite()->asset('images/empty-placeholders/pipedrive.svg') }}"
                                    >

                                    <div class="flex flex-col items-center gap-4">
                                        <div class="flex flex-col items-center gap-2">
                                            <p class="text-xl font-semibold dark:text-white">
                                                @lang('admin::app.initiatives.index.kanban.empty-list')
                                            </p>

                                            <p class="text-gray-400 dark:text-gray-400">
                                                @lang('admin::app.initiatives.index.kanban.empty-list-description')
                                            </p>
                                        </div>

                                        @if (bouncer()->hasPermission('initiatives.create'))
                                            <a
                                                :href="'{{ route('admin.initiatives.create') }}' + '?stage_id=' + stage.id"
                                                class="secondary-button"
                                            >
                                                @lang('admin::app.initiatives.index.kanban.create-initiative-btn')
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </template>

                            <!-- Initiative Card -->
                            <template #item="{ element, index }">
                                {!! view_render_event('admin.initiatives.index.kanban.content.stage.body.card.before') !!}

                                <a
                                    class="initiative-item flex cursor-pointer flex-col gap-5 rounded-md border border-gray-100 bg-gray-50 p-2 dark:border-gray-400 dark:bg-gray-400"
                                    :href="'{{ route('admin.initiatives.view', 'replaceId') }}'.replace('replaceId', element.id)"
                                >
                                    {!! view_render_event('admin.initiatives.index.kanban.content.stage.body.card.header.before') !!}

                                    <!-- Header -->
                                    <p class="text-medium font-medium">
                                        @{{ element.title }}
                                    </p>
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center gap-1">
                                            <div class="flex flex-col gap-0.5">
                                                <span class="text-xs font-xs">
                                                    @{{ element.person.name }}
                                                </span>

                                                <span class="text-[10px] initiativeing-normal">
                                                    @{{ element.person.organization?.name }}
                                                </span>
                                            </div>
                                        </div>

                                        <div
                                            class="group relative"
                                            v-if="element.rotten_days > 0"
                                        >
                                            <span class="icon-rotten cursor-default text-xl text-rose-600"></span>

                                            <div class="absolute -top-1 right-7 hidden w-max flex-col items-center group-hover:flex">
                                                <span class="whitespace-no-wrap relative rounded-md bg-black px-4 py-2 text-xs initiativeing-none text-white shadow-lg">
                                                    @{{ "@lang('admin::app.initiatives.index.kanban.rotten-days', ['days' => 'replaceDays'])".replace('replaceDays', element.rotten_days) }}
                                                </span>

                                                <div class="absolute -right-1 top-2 h-3 w-3 rotate-45 bg-black"></div>
                                            </div>
                                        </div>
                                    </div>

                                    {!! view_render_event('admin.initiatives.index.kanban.content.stage.body.card.header.after') !!}

                                    {!! view_render_event('admin.initiatives.index.kanban.content.stage.body.card.title.before') !!}

                                    <!-- Initiative Title -->

                                    {!! view_render_event('admin.initiatives.index.kanban.content.stage.body.card.title.after') !!}

                                    <div class="flex flex-wrap gap-1">
                                        <div
                                            class="flex items-center gap-1 rounded-xl bg-gray-200 px-2 py-1 text-medium font-medium dark:bg-gray-800 dark:text-white"
                                            v-if="element.user"
                                        >
                                            <span class="icon-settings-user text-sm"></span>

                                            @{{ element.user.name }}
                                        </div>

                                        <div class="rounded-xl bg-gray-200 px-2 py-1 text-xs font-medium dark:bg-gray-800 dark:text-white">
                                            @{{ element.source.name }}
                                        </div>

                                        <div class="rounded-xl bg-gray-200 px-2 py-1 text-xs font-medium dark:bg-gray-800 dark:text-white">
                                            @{{ element.type.name }}
                                        </div>

                                        <!-- Tags -->
                                        <template v-for="tag in element.tags">
                                            {!! view_render_event('admin.initiatives.index.kanban.content.stage.body.card.tag.before') !!}

                                            <div
                                                class="rounded-xl bg-gray-200 px-2 py-1 text-xs font-medium dark:bg-gray-800"
                                                :style="{
                                                    backgroundColor: tag.color,
                                                    color: tagTextColor[tag.color]
                                                }"
                                            >
                                                @{{ tag.name }}
                                            </div>

                                            {!! view_render_event('admin.initiatives.index.kanban.content.stage.body.card.tag.after') !!}
                                        </template>
                                    </div>
                                </a>

                                {!! view_render_event('admin.initiatives.index.kanban.content.stage.body.card.after') !!}
                            </template>
                        </draggable>

                        {!! view_render_event('admin.initiatives.index.kanban.content.stage.body.after') !!}
                    </div>
                </div>

                {!! view_render_event('admin.initiatives.index.kanban.content.after') !!}
            </div>
        </template>
    </script>

    <script type="module">
        app.component('v-initiatives-kanban', {
            template: '#v-initiatives-kanban-template',

            data: function () {
                return {
                    available: {
                        columns: @json($columns),
                    },

                    applied: {
                        filters: {
                            columns: [],
                        }
                    },

                    stages: @json($pipeline->stages->toArray()),

                    stageInitiatives: {},

                    isLoading: true,

                    tagTextColor: {
                        '#FEE2E2': '#DC2626',
                        '#FFEDD5': '#EA580C',
                        '#FEF3C7': '#D97706',
                        '#FEF9C3': '#CA8A04',
                        '#ECFCCB': '#65A30D',
                        '#DCFCE7': '#16A34A',
                    },
                };
            },

            computed: {
                totalStagesAmount() {
                    let totalAmount = 0;

                    for (let [key, stage] of Object.entries(this.stageInitiatives)) {
                        totalAmount += parseFloat(stage.initiative_value);
                    }

                    return totalAmount;
                }
            },

            mounted: function () {
                this.boot();
            },

            methods: {
                /**
                 * Initialization: This function checks for any previously saved filters in local storage and applies them as needed.
                 *
                 * @returns {void}
                 */
                 boot() {
                    let kanbans = this.getKanbans();

                    if (kanbans?.length) {
                        const currentKanban = kanbans.find(({ src }) => src === this.src);

                        if (currentKanban) {
                            this.applied.filters = currentKanban.applied.filters;

                            this.get()
                                .then(response => {
                                    for (let [stageId, data] of Object.entries(response.data)) {
                                        this.stageInitiatives[stageId] = data;
                                    }
                                });

                            return;
                        }
                    }

                    this.get()
                        .then(response => {
                            for (let [stageId, data] of Object.entries(response.data)) {
                                this.stageInitiatives[stageId] = data;
                            }
                        });
                },

                /**
                 * Fetches the initiatives based on the applied filters.
                 *
                 * @param {object} requestedParams - The requested parameters.
                 * @returns {Promise} The promise object representing the request.
                 */
                get(requestedParams = {}) {
                    let params = {
                        search: '',
                        searchFields: '',
                        pipeline_id: "{{ request('pipeline_id') }}",
                        limit: 10,
                    };

                    this.applied.filters.columns.forEach((column) => {
                        if (column.index === 'all') {
                            if (! column.value.length) {
                                return;
                            }

                            params['search'] += `title:${column.value.join(',')};`;
                            params['searchFields'] += `title:like;`;

                            return;
                        }

                        /**
                         * If the column is a searchable dropdown, then we need to append the column value
                         * with the column label. Otherwise, we can directly append the column value.
                         */
                        params['search'] += column.filterable_type === 'searchable_dropdown'
                            ? `${column.index}:${column.value.map(option => option.value).join(',')};`
                            : `${column.index}:${column.value.join(',')};`;

                        params['searchFields'] += `${column.index}:${column.search_field};`;
                    });

                    return this.$axios
                        .get("{{ route('admin.initiatives.get') }}", {
                            params: {
                                ...params,

                                ...requestedParams,
                            }
                        })
                        .then(response => {
                            this.isLoading = false;

                            this.updateKanbans();

                            return response;
                        })
                        .catch(error => {
                            console.log(error)
                        });
                },

                /**
                 * Filters the initiatives based on the applied filters.
                 *
                 * @param {object} filters - The filters to be applied.
                 * @returns {void}
                 */
                filter(filters) {
                    this.applied.filters.columns = [
                        ...(this.applied.filters.columns.filter((column) => column.index === 'all')),
                        ...filters.columns,
                    ];

                    this.get()
                        .then(response => {
                            for (let [stageId, data] of Object.entries(response.data)) {
                                this.stageInitiatives[stageId] = data;
                            }
                        });
                },

                /**
                 * Searches the initiatives based on the applied filters.
                 *
                 * @param {object} filters - The filters to be applied.
                 * @returns {void}
                 */
                search(filters) {
                    this.applied.filters.columns = [
                        ...(this.applied.filters.columns.filter((column) => column.index !== 'all')),
                        ...filters.columns,
                    ];

                    this.get()
                        .then(response => {
                            for (let [stageId, data] of Object.entries(response.data)) {
                                this.stageInitiatives[stageId] = data;
                            }
                        });
                },

                /**
                 * Appends the initiatives to the stage.
                 *
                 * @param {object} params - The parameters to be appended.
                 * @returns {void}
                 */
                append(params) {
                    this.get(params)
                        .then(response => {
                            for (let [stageId, data] of Object.entries(response.data)) {
                                if (! this.stageInitiatives[stageId]) {
                                    this.stageInitiatives[stageId] = data;
                                } else {
                                    this.stageInitiatives[stageId].initiatives.data = this.stageInitiatives[stageId].initiatives.data.concat(data.initiatives.data);

                                    this.stageInitiatives[stageId].initiatives.meta = data.initiatives.meta;
                                }
                            }
                        });
                },

                /**
                 * Updates the stage with the latest initiative data.
                 *
                 * @param {object} stage - The stage object.
                 * @param {object} event - The event object.
                 * @returns {void}
                 */
                updateStage: function (stage, event) {
                    if (event.removed) {
                        stage.initiative_value = parseFloat(stage.initiative_value) - parseFloat(event.removed.element.initiative_value);

                        this.stageInitiatives[stage.id].initiatives.meta.total = this.stageInitiatives[stage.id].initiatives.meta.total - 1;

                        return;
                    }

                    stage.initiative_value = parseFloat(stage.initiative_value) + parseFloat(event.added.element.initiative_value);

                    this.stageInitiatives[stage.id].initiatives.meta.total = this.stageInitiatives[stage.id].initiatives.meta.total + 1;

                    this.$axios
                        .put("{{ route('admin.initiatives.stage.update', 'replace') }}".replace('replace', event.added.element.id), {
                            'initiative_pipeline_stage_id': stage.id
                        })
                        .then(response => {
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => {
                            this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });
                        });
                },

                /**
                 * Handles the scroll event on the stage initiatives.
                 *
                 * @param {object} stage - The stage object.
                 * @param {object} event - The scroll event.
                 * @returns {void}
                 */
                handleScroll(stage, event) {
                    const bottom = event.target.scrollHeight - event.target.scrollTop === event.target.clientHeight

                    if (! bottom) {
                        return;
                    }

                    if (this.stageInitiatives[stage.id].initiatives.meta.current_page == this.stageInitiatives[stage.id].initiatives.meta.last_page) {
                        return;
                    }

                    this.append({
                        pipeline_stage_id: stage.id,
                        pipeline_id: stage.initiative_pipeline_id,
                        page: this.stageInitiatives[stage.id].initiatives.meta.current_page + 1,
                        limit: 10,
                    });
                },

                //=======================================================================================
                // Support for previous applied values in kanbans. All code is based on local storage.
                //=======================================================================================

                /**
                 * Updates the kanbans stored in local storage with the latest data.
                 *
                 * @returns {void}
                 */
                 updateKanbans() {
                    let kanbans = this.getKanbans();

                    if (kanbans?.length) {
                        const currentKanban = kanbans.find(({ src }) => src === this.src);

                        if (currentKanban) {
                            kanbans = kanbans.map(kanban => {
                                if (kanban.src === this.src) {
                                    return {
                                        ...kanban,
                                        requestCount: ++kanban.requestCount,
                                        available: this.available,
                                        applied: this.applied,
                                    };
                                }

                                return kanban;
                            });
                        } else {
                            kanbans.push(this.getKanbanInitialProperties());
                        }
                    } else {
                        kanbans = [this.getKanbanInitialProperties()];
                    }

                    this.setKanbans(kanbans);
                },

                /**
                 * Returns the initial properties for a kanban.
                 *
                 * @returns {object} Initial properties for a kanban.
                 */
                getKanbanInitialProperties() {
                    return {
                        src: this.src,
                        requestCount: 0,
                        available: this.available,
                        applied: this.applied,
                    };
                },

                /**
                 * Returns the storage key for kanbans in local storage.
                 *
                 * @returns {string} Storage key for kanbans.
                 */
                getKanbansStorageKey() {
                    return 'kanbans';
                },

                /**
                 * Retrieves the kanbans stored in local storage.
                 *
                 * @returns {Array} Kanbans stored in local storage.
                 */
                getKanbans() {
                    let kanbans = localStorage.getItem(
                        this.getKanbansStorageKey()
                    );

                    return JSON.parse(kanbans) ?? [];
                },

                /**
                 * Sets the kanbans in local storage.
                 *
                 * @param {Array} kanbans - Kanbans to be stored in local storage.
                 * @returns {void}
                 */
                setKanbans(kanbans) {
                    localStorage.setItem(
                        this.getKanbansStorageKey(),
                        JSON.stringify(kanbans)
                    );
                },
            }
        });
    </script>
@endPushOnce
