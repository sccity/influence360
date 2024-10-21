@if (isset($attribute))
    <v-email-component
        :attribute='@json($attribute)'
        :value='@json($value ?? [])'
    >
    </v-email-component>
@endif

@pushOnce('scripts')
    <script type="text/x-template" id="v-email-component-template">
        <div>
            <div v-for="(email, index) in emails" :key="index" class="mb-2 flex items-center">
                <input
                    type="text"
                    v-model="email.value"
                    :name="`${attribute.code}[${index}][value]`"
                    class="w-full rounded rounded-r-none border border-gray-300 px-2.5 py-2 text-sm"
                    :placeholder="attribute.name"
                />

                <select
                    v-model="email.label"
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
                    v-if="emails.length > 1"
                >
                    <i class="icon-delete text-2xl"></i>
                </button>
            </div>

            <button
                type="button"
                class="mt-2 text-blue-600"
                @click="add"
                v-if="emails.length < maxEmptyFields"
            >
                Add Email
            </button>
        </div>
    </script>

    <script type="module">
        app.component('v-email-component', {
            template: '#v-email-component-template',

            props: {
                attribute: Object,
                value: {
                    type: [Array, String],
                    default: () => []
                }
            },

            data() {
                return {
                    emails: [],
                    maxEmptyFields: 3,
                };
            },

            created() {
                this.emails = this.normalizeEmails(this.value);
            },

            methods: {
                normalizeEmails(value) {
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
                    if (this.emails.length < this.maxEmptyFields) {
                        this.emails.push({'value': '', 'label': 'work'});
                    }
                },

                remove(index) {
                    this.emails.splice(index, 1);
                    if (this.emails.length === 0) {
                        this.add();
                    }
                }
            }
        });
    </script>
@endPushOnce
