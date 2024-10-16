<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.bill-files.index.title')
    </x-slot>

    <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.bill-files.index.title')
        </p>
    </div>

    {!! view_render_event('admin.bill-files.index.before') !!}

    <x-admin::datagrid src="{{ route('admin.bill-files.index') }}"></x-admin::datagrid>
    {!! view_render_event('admin.bill-files.index.after') !!}
</x-admin::layouts>
