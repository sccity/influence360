@if (isset($attribute))
    <v-phone-component
        :attribute='@json($attribute)'
        :value='@json($value ?? [])'
    >
    </v-phone-component>
@endif

@pushOnce('scripts')
    <script type="text/x-template" id="v-phone-component-template">
        <div>
            <div v-for="(phone, index) in phones" :key="index" class="mb-2 flex items-center">
                <input
                    type="text"
                    v-model="phone.value"
                    :name="`${attribute.code}[${index}][value]`"
                    class="w-full rounded rounded-r-none border border-gray-300 px-2.5 py-2 text-sm"
                    :placeholder="attribute.name"
                />

                <select
                    v-model="phone.label"
                    :name="`${attribute.code}[${index}][label]`"
                    class="rounded rounded-l-none border border-gray-300 px-2.5 py-2 text-sm"
                >
                    <option value="work">@lang('admin::app.common.custom-attributes.work')</option>
                    <option value="home">@lang('admin::app.common.custom-attributes.home')</option>
                </select>

                <button
                    type="button"
                    class="ml-2 text-red-600"
                    @click="remove(index)"
                    v-if="phones.length > 1"
                >
                    <i class="icon-delete text-2xl"></i>
                </button>
            </div>

            <button
                type="button"
                class="mt-2 text-blue-600"
                @click="add"
                v-if="phones.length < maxEmptyFields"
            >
                Add Phone
            </button>
        </div>
    </script>

    <script type="module">
        app.component('v-phone-component', {
            template: '#v-phone-component-template',

            props: {
                attribute: Object,
                value: {
                    type: [Array, String],
                    default: () => []
                }
            },

            data() {
                return {
                    phones: [],
                    maxEmptyFields: 3,
                };
            },

            created() {
                this.phones = this.normalizePhones(this.value);
            },

            methods: {
                normalizePhones(value) {
                    if (typeof value === 'string') {
                        try {
                            return JSON.parse(value);
                        } catch (e) {
                            return [{'value': '', 'label': 'work'}];
                        }
                    } else if (Array.isArray(value) && value.length) {
                        return value;
                    } else if (typeof value === 'object' && value !== null) {
                        return Object.entries(value).map(([label, value]) => ({ label, value }));
                    } else {
                        return [{'value': '', 'label': 'work'}];
                    }
                },

                add() {
                    if (this.phones.length < this.maxEmptyFields) {
                        this.phones.push({'value': '', 'label': 'work'});
                    }
                },

                remove(index) {
                    this.phones.splice(index, 1);
                    if (this.phones.length === 0) {
                        this.add();
                    }
                }
            }
        });
    </script>
@endPushOnce
