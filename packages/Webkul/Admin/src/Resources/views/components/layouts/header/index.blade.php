<header class="sticky top-0 z-[10001] flex items-center justify-between border-b border-gray-200 bg-white px-4 py-2.5 transition-all dark:border-gray-800 dark:bg-gray-900">
    <!-- logo -->
    <div class="flex items-center gap-1.5">
        <i class="icon-menu hidden cursor-pointer rounded-md p-1.5 text-2xl hover:bg-gray-100 dark:hover:bg-gray-950 max-lg:block"></i>
        <a href="{{ route('admin.dashboard.index') }}">
            <script>
                function applyLogoColor() {
                    const isDarkMode = document.documentElement.classList.contains('dark');
                    const influenceText = document.querySelector('#logo-text .influence');

                    if (isDarkMode) {
                        influenceText.style.color = 'white';
                    } else {
                        influenceText.style.color = 'black';
                    }
                }
                document.addEventListener('DOMContentLoaded', applyLogoColor);
                const observer = new MutationObserver(() => {
                    applyLogoColor();
                });
                observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['class']
                });
            </script>
            <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
            <span id="logo-text" class="h-10 text-2xl" style="font-family: 'Montserrat', sans-serif;">
                <span class="font-semibold influence">INFLUENCE</span>
                <span class="font-bold logo-360" style="color: rgb(14, 144, 217);">360</span>
            </span>
        </a>
    </div>

    <div class="flex items-center gap-1.5">
        <!-- Mega Search Bar Vue Component -->
        <v-mega-search>
            <div class="relative flex w-[525px] max-w-[525px] items-center max-lg:w-[400px] ltr:ml-2.5 rtl:mr-2.5">
                <i class="icon-search absolute top-1.5 flex items-center text-2xl ltr:left-3 rtl:right-3"></i>

                <input
                    type="text"
                    class="block w-full rounded-3xl border bg-white px-10 py-1.5 initiativeing-6 text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                    placeholder="@lang('admin::app.components.layouts.header.mega-search.title')"
                >
            </div>
        </v-mega-search>

        <!-- Quick create section -->
        <div>
            @if (bouncer()->hasPermission('initiatives.create')
                || bouncer()->hasPermission('contacts.persons.create')
                || bouncer()->hasPermission('contacts.organizations.create')
                || bouncer()->hasPermission('settings.automation.attributes.create')
                || bouncer()->hasPermission('settings.user.roles.create')
                || bouncer()->hasPermission('settings.user.users.create')
            )
                <x-admin::dropdown position="bottom-right">
                    <x-slot:toggle>
                        <!-- Toggle Button -->
                        <button class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-brandColor text-white">
                            <i class="icon-add text-2xl"></i>
                        </button>
                    </x-slot>

                    <!-- Dropdown Content -->
                    <x-slot:content class="mt-2 !p-0">
                        <div class="relative px-2 py-4">
                            <div class="grid grid-cols-3 gap-2 text-center">
                                <!-- Link to create new Initiative -->
                                @if (bouncer()->hasPermission('initiatives.create'))
                                    <div class="rounded-lg bg-white p-2 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-950">
                                        <a href="{{ route('admin.initiatives.create') }}">
                                            <div class="flex flex-col gap-1">
                                                <i class="icon-initiatives text-2xl text-gray-600"></i>

                                                <span class="font-medium dark:text-gray-300">@lang('admin::app.layouts.initiative')</span>
                                            </div>
                                        </a>
                                    </div>
                                @endif

                                <!-- Link to create new Person-->
                                @if (bouncer()->hasPermission('contacts.persons.create'))
                                    <div class="rounded-lg bg-white p-2 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-950">
                                        <a href="{{ route('admin.contacts.persons.create') }}">
                                            <div class="flex flex-col gap-1">
                                                <i class="icon-settings-user text-2xl text-gray-600"></i>

                                                <span class="font-medium dark:text-gray-300">@lang('admin::app.layouts.person')</span>
                                            </div>
                                        </a>
                                    </div>
                                @endif

                                <!-- Link to create new Organizations -->
                                @if (bouncer()->hasPermission('contacts.organizations.create'))
                                    <div class="rounded-lg bg-white p-2 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-950">
                                        <a href="{{ route('admin.contacts.organizations.create') }}">
                                            <div class="flex flex-col gap-1">
                                                <i class="icon-organization text-2xl text-gray-600"></i>

                                                <span class="font-medium dark:text-gray-300">@lang('admin::app.layouts.organization')</span>
                                            </div>
                                        </a>
                                    </div>
                                @endif

                                <!-- Link to create new Attributes -->
                                @if (bouncer()->hasPermission('settings.automation.attributes.create'))
                                    <div class="rounded-lg bg-white p-2 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-950">
                                        <a href="{{ route('admin.settings.attributes.create') }}">
                                            <div class="flex flex-col gap-1">
                                                <i class="icon-attribute text-2xl text-gray-600"></i>

                                                <span class="font-medium dark:text-gray-300">@lang('admin::app.layouts.attribute')</span>
                                            </div>
                                        </a>
                                    </div>
                                @endif

                                <!-- Link to create new Role -->
                                @if (bouncer()->hasPermission('settings.user.roles.create'))
                                    <div class="rounded-lg bg-white p-2 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-950">
                                        <a href="{{ route('admin.settings.roles.create') }}">
                                            <div class="flex flex-col gap-1">
                                                <i class="icon-role text-2xl text-gray-600"></i>

                                                <span class="font-medium dark:text-gray-300">@lang('admin::app.layouts.role')</span>
                                            </div>
                                        </a>
                                    </div>
                                @endif

                                <!-- Link to create new User-->
                                @if (bouncer()->hasPermission('settings.user.users.create'))
                                    <div class="rounded-lg bg-white p-2 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-950">
                                        <a href="{{ route('admin.settings.users.index') }}">
                                            <div class="flex flex-col gap-1">
                                                <i class="icon-user text-2xl text-gray-600"></i>

                                                <span class="font-medium dark:text-gray-300">@lang('admin::app.layouts.user')</span>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </x-slot>
                </x-admin::dropdown>
            @endif
        </div>
    </div>

    <div class="flex items-center gap-2.5">
        <!-- Dark mode -->
        <v-dark>
            <div class="flex">
                <span
                    class="{{ request()->cookie('dark_mode') ? 'icon-light' : 'icon-dark' }} p-1.5 rounded-md text-2xl cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                ></span>
            </div>
        </v-dark>

        <!-- Admin profile -->
        <x-admin::dropdown position="bottom-right">
            <x-slot:toggle>
                @if (auth()->guard('user')->user()->image)
                    <button class="flex h-9 w-9 cursor-pointer overflow-hidden rounded-full hover:opacity-80 focus:opacity-80">
                        <img
                            src="{{ auth()->guard('user')->user()->image_url }}"
                            class="w-full"
                        />
                    </button>
                @else
                    <button class="flex h-9 w-9 cursor-pointer items-center justify-center rounded-full bg-pink-400 font-semibold initiativeing-6 text-white">
                        {{ substr(auth()->guard('user')->user()->name, 0, 1) }}
                    </button>
                @endif
            </x-slot>

            <!-- Admin Dropdown -->
            <x-slot:content class="!p-0">
                <div class="flex items-center gap-1.5 border border-b-gray-300 px-5 py-2.5 dark:border-gray-800">
                    <img
                        src="{{ url('cache/logo.png') }}"
                        width="24"
                        height="24"
                    />

                    <!-- Version -->
                    <p class="text-gray-400">
                        Version: v{{ core()->version() }}
                    </p>
                </div>

                <div class="grid gap-1 pb-2.5">
                    <a
                        class="cursor-pointer px-5 py-2 text-base text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                        href="{{ route('admin.user.account.edit') }}"
                    >
                        My Account
                    </a>

                    <!--Admin logout-->
                    <x-admin::form
                        method="DELETE"
                        action="{{ route('admin.session.destroy') }}"
                        id="adminLogout"
                    >
                    </x-admin::form>

                    <a
                        class="cursor-pointer px-5 py-2 text-base text-gray-800 hover:bg-gray-100 dark:text-white dark:hover:bg-gray-950"
                        href="{{ route('admin.session.destroy') }}"
                        onclick="event.preventDefault(); document.getElementById('adminLogout').submit();"
                    >
                        Logout
                    </a>
                </div>
            </x-slot>
        </x-admin::dropdown>
    </div>
</header>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-mega-search-template"
    >
        <div class="relative flex w-[525px] max-w-[525px] items-center max-lg:w-[400px] ltr:ml-2.5 rtl:mr-2.5">
            <i class="icon-search absolute top-1.5 flex items-center text-2xl ltr:left-3 rtl:right-3"></i>

            <input
                type="text"
                class="peer block w-full rounded-3xl border bg-white px-10 py-1.5 initiativeing-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                :class="{'border-gray-400': isDropdownOpen}"
                placeholder="@lang('Search')"
                v-model.lazy="searchTerm"
                @click="searchTerm.length >= 2 ? isDropdownOpen = true : {}"
                v-debounce="500"
            >

            <div
                class="absolute top-10 z-10 w-full rounded-lg border bg-white shadow-[0px_0px_0px_0px_rgba(0,0,0,0.10),0px_1px_3px_0px_rgba(0,0,0,0.10),0px_5px_5px_0px_rgba(0,0,0,0.09),0px_12px_7px_0px_rgba(0,0,0,0.05),0px_22px_9px_0px_rgba(0,0,0,0.01),0px_34px_9px_0px_rgba(0,0,0,0.00)] dark:border-gray-800 dark:bg-gray-900"
                v-if="isDropdownOpen"
            >
                <!-- Search Tabs -->
                <div class="flex border-b text-sm text-gray-600 dark:border-gray-800 dark:text-gray-300">
                    <div
                        class="cursor-pointer p-4 hover:bg-gray-100 dark:hover:bg-gray-950"
                        :class="{ 'border-b-2 border-brandColor': activeTab == tab.key }"
                        v-for="tab in tabs"
                        @click="activeTab = tab.key; search();"
                    >
                        @{{ tab.title }}
                    </div>
                </div>

                <!-- Searched Results -->
                <template v-if="activeTab == 'products'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.products />
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <template v-for="product in searchedResults.products">
                                <a
                                    :href="'{{ route('admin.products.view', ':id') }}'.replace(':id', product.id)"
                                    class="flex cursor-pointer justify-between gap-2.5 border-b border-slate-300 p-4 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-gray-950"
                                >
                                    <!-- Left Information -->
                                    <div class="flex gap-2.5">
                                        <!-- Details -->
                                        <div class="grid place-content-start gap-1.5">
                                            <p class="text-base font-semibold text-gray-600 dark:text-gray-300">
                                                @{{ product.name }}
                                            </p>

                                            <p class="text-gray-500">
                                                @{{ "@lang(':sku')".replace(':sku', product.sku) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Right Information -->
                                    <div class="grid place-content-center gap-1 text-right">
                                        <!-- Formatted Price -->
                                        <p class="font-semibold text-gray-600 dark:text-gray-300">
                                            @{{ $admin.formatPrice(product.price) }}
                                        </p>
                                    </div>
                                </a>
                            </template>

                        </div>

                        <div class="flex border-t p-3 dark:border-gray-800">
                            <template v-if="searchedResults.products.length">
                                <a
                                    :href="'{{ route('admin.products.index') }}?search=:query'.replace(':query', searchTerm)"
                                    class="cursor-pointer text-xs font-semibold text-brandColor transition-all hover:underline"
                                >

                                    @{{ `@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-products')`.replace(':query', searchTerm).replace(':count', searchedResults.products.length) }}
                                </a>
                            </template>

                            <template v-else>
                                <a
                                    href="{{ route('admin.products.index') }}"
                                    class="cursor-pointer text-xs font-semibold text-brandColor transition-all hover:underline"
                                >
                                    @lang('admin::app.components.layouts.header.mega-search.explore-all-products')
                                </a>
                            </template>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'initiatives'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.initiatives />
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <template v-for="initiative in searchedResults.initiatives">
                                <a
                                    :href="'{{ route('admin.initiatives.view', ':id') }}'.replace(':id', initiative.id)"
                                    class="flex cursor-pointer justify-between gap-2.5 border-b border-slate-300 p-4 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-gray-950"
                                >
                                    <!-- Left Information -->
                                    <div class="flex gap-2.5">
                                        <!-- Details -->
                                        <div class="grid place-content-start gap-1.5">
                                            <p class="text-base font-semibold text-gray-600 dark:text-gray-300">
                                                @{{ initiative.title }}
                                            </p>

                                            <p class="text-gray-500">
                                                @{{ initiative.description }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Right Information -->
                                    <div class="grid place-content-center gap-1 text-right">
                                        <!-- Formatted Price -->
                                        <p class="font-semibold text-gray-600 dark:text-gray-300">
                                            @{{ $admin.formatPrice(initiative.initiative_value) }}
                                        </p>
                                    </div>
                                </a>
                            </template>
                        </div>

                        <div class="flex border-t p-3 dark:border-gray-800">
                            <template v-if="searchedResults.initiatives.length">
                                <a
                                    :href="'{{ route('admin.initiatives.index') }}?search=:query'.replace(':query', searchTerm)"
                                    class="cursor-pointer text-xs font-semibold text-brandColor transition-all hover:underline"
                                >
                                    @{{ `@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-initiatives')`.replace(':query', searchTerm).replace(':count', searchedResults.initiatives.length) }}
                                </a>
                            </template>

                            <template v-else>
                                <a
                                    href="{{ route('admin.initiatives.index') }}"
                                    class="cursor-pointer text-xs font-semibold text-brandColor transition-all hover:underline"
                                >
                                    @lang('admin::app.components.layouts.header.mega-search.explore-all-initiatives')
                                </a>
                            </template>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'persons'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.persons />
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <template v-for="person in searchedResults.persons">
                                <a
                                    :href="'{{ route('admin.contacts.persons.view', ':id') }}'.replace(':id', person.id)"
                                    class="flex cursor-pointer justify-between gap-2.5 border-b border-slate-300 p-4 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-gray-950"
                                >
                                    <!-- Left Information -->
                                    <div class="flex gap-2.5">
                                        <!-- Details -->
                                        <div class="grid place-content-start gap-1.5">
                                            <p class="text-base font-semibold text-gray-600 dark:text-gray-300">
                                                @{{ person.name }}
                                            </p>

                                            <p class="text-gray-500">
                                                @{{ person.emails.map((item) => `${item.value}(${item.label})`).join(', ') }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>

                        <div class="flex border-t p-3 dark:border-gray-800">
                            <template v-if="searchedResults.persons.length">
                                <a
                                    :href="'{{ route('admin.contacts.persons.index') }}?search=:query'.replace(':query', searchTerm)"
                                    class="cursor-pointer text-xs font-semibold text-brandColor transition-all hover:underline"
                                >
                                    @{{ `@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-contacts')`.replace(':query', searchTerm).replace(':count', searchedResults.persons.length) }}
                                </a>
                            </template>

                            <template v-else>
                                <a
                                    href="{{ route('admin.contacts.persons.index') }}"
                                    class="cursor-pointer text-xs font-semibold text-brandColor transition-all hover:underline"
                                >
                                    @lang('admin::app.components.layouts.header.mega-search.explore-all-contacts')
                                </a>
                            </template>
                        </div>
                    </template>
                </template>

                <template v-if="activeTab == 'quotes'">
                    <template v-if="isLoading">
                        <x-admin::shimmer.header.mega-search.quotes />
                    </template>

                    <template v-else>
                        <div class="grid max-h-[400px] overflow-y-auto">
                            <template v-for="quote in searchedResults.quotes">
                                <a
                                    :href="'{{ route('admin.quotes.edit', ':id') }}'.replace(':id', quote.id)"
                                    class="flex cursor-pointer justify-between gap-2.5 border-b border-slate-300 p-4 last:border-b-0 hover:bg-gray-100 dark:border-gray-800 dark:hover:bg-gray-950"
                                >
                                    <!-- Left Information -->
                                    <div class="flex gap-2.5">
                                        <!-- Details -->
                                        <div class="grid place-content-start gap-1.5">
                                            <p class="text-base font-semibold text-gray-600 dark:text-gray-300">
                                                @{{ quote.subject }}
                                            </p>

                                            <p class="text-gray-500">
                                                @{{ quote.description }}
                                            </p>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>

                        <div class="flex border-t p-3 dark:border-gray-800">
                            <template v-if="searchedResults.quotes.length">
                                <a
                                    :href="'{{ route('admin.quotes.index') }}?search=:query'.replace(':query', searchTerm)"
                                    class="cursor-pointer text-xs font-semibold text-brandColor transition-all hover:underline"
                                >
                                    @{{ `@lang('admin::app.components.layouts.header.mega-search.explore-all-matching-quotes')`.replace(':query', searchTerm).replace(':count', searchedResults.quotes.length) }}
                                </a>
                            </template>

                            <template v-else>
                                <a
                                    href="{{ route('admin.quotes.index') }}"
                                    class="cursor-pointer text-xs font-semibold text-brandColor transition-all hover:underline"
                                >
                                    @lang('admin::app.components.layouts.header.mega-search.explore-all-quotes')
                                </a>
                            </template>
                        </div>
                    </template>
                </template>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('v-mega-search', {
            template: '#v-mega-search-template',

            data() {
                return {
                    activeTab: 'initiatives',
                    isDropdownOpen: false,
                    searchTerm: '',
                    isLoading: false,

                    searchedResults: {
                        initiatives: [],
                        products: [],
                        persons: [],
                        quotes: [],
                    },

                    tabs: [
                        {
                            key: 'initiatives',
                            title: "@lang('admin::app.components.layouts.header.mega-search.initiatives')",
                            route: "{{ route('admin.initiatives.index') }}"
                        },
                        {
                            key: 'products',
                            title: "@lang('admin::app.components.layouts.header.mega-search.products')",
                            route: "{{ route('admin.products.index') }}"
                        },
                        {
                            key: 'persons',
                            title: "@lang('admin::app.components.layouts.header.mega-search.persons')",
                            route: "{{ route('admin.contacts.persons.index') }}"
                        },
                        {
                            key: 'quotes',
                            title: "@lang('admin::app.components.layouts.header.mega-search.quotes')",
                            route: "{{ route('admin.quotes.index') }}"
                        },
                    ],
                };
            },

            methods: {
                search() {
                    this.isLoading = true;

                    if (this.searchTerm.length <= 1) {
                        this.searchedResults[this.activeTab] = [];
                        this.isLoading = false;
                        return;
                    }

                    const currentTab = this.tabs.find(tab => tab.key === this.activeTab);

                    this.$axios.get(currentTab.route, {
                        params: {
                            query: this.searchTerm,
                        }
                    })
                    .then(response => {
                        this.searchedResults[this.activeTab] = response.data.data || [];
                        this.isLoading = false;
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        this.searchedResults[this.activeTab] = [];
                        this.isLoading = false;
                    });
                },
            },
        });
    </script>

    <script
        type="text/x-template"
        id="v-dark-template"
    >
        <div class="flex">
            <span
                class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                :class="[isDarkMode ? 'icon-light' : 'icon-dark']"
                @click="toggle"
            ></span>
        </div>
    </script>

    <script type="module">
        app.component('v-dark', {
            template: '#v-dark-template',

            data() {
                return {
                    isDarkMode: {{ request()->cookie('dark_mode') ?? 0 }},

                    logo: "{{ vite()->asset('images/logo.svg') }}",

                    dark_logo: "{{ vite()->asset('images/dark-logo.svg') }}",
                };
            },

            methods: {
                toggle() {
                    this.isDarkMode = parseInt(this.isDarkModeCookie()) ? 0 : 1;

                    var expiryDate = new Date();

                    expiryDate.setMonth(expiryDate.getMonth() + 1);

                    document.cookie = 'dark_mode=' + this.isDarkMode + '; path=/; expires=' + expiryDate.toGMTString();

                    document.documentElement.classList.toggle('dark', this.isDarkMode === 1);

                    if (this.isDarkMode) {
                        this.$emitter.emit('change-theme', 'dark');

                        document.getElementById('logo-image').src = this.dark_logo;
                    } else {
                        this.$emitter.emit('change-theme', 'light');

                        document.getElementById('logo-image').src = this.logo;
                    }
                },

                isDarkModeCookie() {
                    const cookies = document.cookie.split(';');

                    for (const cookie of cookies) {
                        const [name, value] = cookie.trim().split('=');

                        if (name === 'dark_mode') {
                            return value;
                        }
                    }

                    return 0;
                },
            },
        });
    </script>

    <script
        data-embed-id="1fe0b358-de3c-4b4a-aea0-cd1f40ff19bb"
        data-base-api-url="https://gpt.santaclarautah.gov/api/embed"
        src="https://gpt.santaclarautah.gov/embed/anythingllm-chat-widget.min.js">
    </script>
@endPushOnce

