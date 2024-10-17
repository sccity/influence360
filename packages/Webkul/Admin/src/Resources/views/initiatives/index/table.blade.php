{!! view_render_event('admin.initiatives.index.table.before') !!}

<x-admin::datagrid :src="route('admin.initiatives.index')">
    <!-- DataGrid Shimmer -->
    <x-admin::shimmer.datagrid />

    <x-slot:toolbar-right-after>
        @include('admin::initiatives.index.view-switcher')
    </x-slot>
</x-admin::datagrid>

{!! view_render_event('admin.initiatives.index.table.after') !!}