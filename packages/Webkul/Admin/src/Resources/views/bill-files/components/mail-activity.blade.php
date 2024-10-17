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
                ref="mailActionForm"
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

                                <x-admin::form.control-group.control
                                    type="textarea"
                                    name="comment"
                                    rules="required"
                                    :label="trans('admin::app.components.activities.actions.mail.content')"
                                />

                                <x-admin::form.control-group.error control-name="comment" />
                            </x-admin::form.control-group>

                            <!-- Participants -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.components.activities.actions.mail.participants')
                                </x-admin::form.control-group.label>

                                <v-multi-lookup-component></v-multi-lookup-component>
                            </x-admin::form.control-group>

                            <!-- Date Sent -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.components.activities.actions.mail.date-sent')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="date"
                                    name="schedule_from"
                                    :value="date('Y-m-d')"
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
    </script>

    <script type="text/x-template" id="v-bill-file-participants-template">
        <div>
            <select name="participants[]" multiple>
                <option v-for="person in persons" :value="person.id">
                    @{{ person.name }}
                </option>
            </select>
        </div>
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

                    // Merge the form data with the params
                    let data = Object.assign({}, params, {
                        type: 'email',
                        [this.entityControlName]: this.entity.id
                    });

                    this.$axios.post("{{ route('admin.activities.store') }}", data)
                        .then(response => {
                            this.isLoading = false;

                            this.$refs.billFileMailModal.close();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.$emitter.emit('on-activity-added', response.data.data);
                        })
                        .catch(error => {
                            this.isLoading = false;

                            if (error.response.status == 422) {
                                this.$refs.mailActionForm.setErrors(error.response.data.errors);
                            } else {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message });

                                this.$refs.billFileMailModal.close();
                            }
                        });
                },
            },
        });

        app.component('v-bill-file-participants', {
            template: '#v-bill-file-participants-template',

            data() {
                return {
                    persons: [],
                };
            },

            mounted() {
                this.getPersons();
            },

            methods: {
                getPersons() {
                    this.$axios.get(route('admin.contacts.persons.search'))
                        .then(response => {
                            this.persons = response.data.data;
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
            },
        });

        app.component('v-multi-lookup-component', {
            template: '#v-multi-lookup-component-template',

            data() {
                return {
                    searchTerm: '',

                    isSearching: {
                        users: false,
                        persons: false,
                    },

                    addedParticipants: {
                        users: [],
                        persons: [],
                    },

                    searchedParticipants: {
                        users: [],
                        persons: [],
                    },

                    searchEnpoints: {
                        users: "{{ route('admin.settings.users.search') }}",
                        persons: "{{ route('admin.contacts.persons.search') }}",
                    },
                };
            },

            methods: {
                search(userType) {
                    if (this.searchTerm.length <= 1) {
                        this.searchedParticipants[userType] = [];
                        this.isSearching[userType] = false;
                        return;
                    }

                    this.isSearching[userType] = true;

                    this.$axios.get(this.searchEnpoints[userType], {
                        params: {
                            query: this.searchTerm,
                        }
                    })
                    .then((response) => {
                        this.searchedParticipants[userType] = response.data.data.filter(participant => 
                            !this.addedParticipants[userType].some(added => added.id === participant.id)
                        );
                        this.isSearching[userType] = false;
                    })
                    .catch((error) => {
                        this.isSearching[userType] = false;
                        console.log(error);
                    });
                },

                add(userType, participant) {
                    this.addedParticipants[userType].push(participant);
                    this.searchTerm = '';
                    this.searchedParticipants[userType] = [];
                },

                remove(userType, participant) {
                    this.addedParticipants[userType] = this.addedParticipants[userType].filter(p => p.id !== participant.id);
                },
            },

            watch: {
                searchTerm() {
                    this.search('users');
                    this.search('persons');
                },
            },
        });
    </script>

    <script type="text/x-template" id="v-multi-lookup-component-template">
        <div class="relative">
            <div class="flex flex-wrap items-center gap-1 rounded border border-gray-300 p-1">
                <template v-for="userType in ['users', 'persons']">
                    <span
                        v-for="participant in addedParticipants[userType]"
                        class="flex items-center gap-1 rounded bg-gray-100 px-2 py-1 text-xs"
                    >
                        <input type="hidden" :name="`participants[${userType}][]`" :value="participant.id">
                        @{{ participant.name }}
                        <span class="cursor-pointer" @click="remove(userType, participant)">&times;</span>
                    </span>
                </template>
                <input
                    type="text"
                    v-model="searchTerm"
                    class="flex-grow border-none p-1 focus:outline-none focus:ring-0"
                    placeholder="Search participants..."
                >
            </div>
            <div v-if="searchTerm.length > 1" class="absolute left-0 z-10 mt-2 w-full rounded border border-gray-300 bg-white shadow-lg">
                <template v-for="userType in ['users', 'persons']">
                    <div v-if="searchedParticipants[userType].length > 0">
                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-2 text-xs font-semibold uppercase text-gray-500">
                            @{{ userType }}
                        </div>
                        <div
                            v-for="participant in searchedParticipants[userType]"
                            class="cursor-pointer px-4 py-2 hover:bg-gray-100"
                            @click="add(userType, participant)"
                        >
                            @{{ participant.name }}
                        </div>
                    </div>
                </template>
                <div v-if="isSearching.users || isSearching.persons" class="px-4 py-2 text-center text-sm text-gray-500">
                    Searching...
                </div>
                <div
                    v-if="!isSearching.users && !isSearching.persons && searchedParticipants.users.length === 0 && searchedParticipants.persons.length === 0"
                    class="px-4 py-2 text-center text-sm text-gray-500"
                >
                    No results found
                </div>
            </div>
        </div>
    </script>
@endPushOnce
