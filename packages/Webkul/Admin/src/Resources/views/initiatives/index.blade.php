<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.initiatives.index.title')
    </x-slot>

    <!-- Header -->
    {!! view_render_event('admin.initiatives.index.header.before') !!}

    <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        {!! view_render_event('admin.initiatives.index.header.left.before') !!}

        <div class="flex flex-col gap-2">
            <div class="flex cursor-pointer items-center">
                <!-- Bredcrumbs -->
                <x-admin::breadcrumbs name="initiatives" />
            </div>

            <div class="text-xl font-bold dark:text-white">
                @lang('admin::app.initiatives.index.title')
            </div>
        </div>

        {!! view_render_event('admin.initiatives.index.header.left.after') !!}

        {!! view_render_event('admin.initiatives.index.header.right.before') !!}

        <div class="flex items-center gap-x-2.5">
            <!-- Create button for Initiatives -->
            <div class="flex items-center gap-x-2.5">
                @if (bouncer()->hasPermission('initiatives.create'))
                    <a
                        href="{{ route('admin.initiatives.create') }}"
                        class="primary-button"
                    >
                        @lang('admin::app.initiatives.index.create-btn')
                    </a>
                @endif
            </div>
        </div>

        {!! view_render_event('admin.initiatives.index.header.right.after') !!}
    </div>

    {!! view_render_event('admin.initiatives.index.header.after') !!}

    {!! view_render_event('admin.initiatives.index.content.before') !!}

    <!-- Content -->
    <div class="mt-3.5">
        @if ((request()->view_type ?? "kanban") == "table")
            @include('admin::initiatives.index.table')
        @else
            @include('admin::initiatives.index.kanban')
        @endif
    </div>

    {!! view_render_event('admin.initiatives.index.content.after') !!}
</x-admin::layouts>