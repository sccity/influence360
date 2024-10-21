{!! view_render_event('admin.bill-files.view.attributes.before', ['billFile' => $billFile]) !!}

<div class="flex w-full flex-col gap-4 border-b border-gray-200 p-4 dark:border-gray-800">
    <h4 class="font-semibold dark:text-white">
        @lang('admin::app.bill-files.view.details')
    </h4>

    <div class="flex flex-col gap-2">
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-300">@lang('admin::app.bill-files.view.billid')</span>
            <span class="font-medium dark:text-white">{{ $billFile->billid }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-300">@lang('admin::app.bill-files.view.name')</span>
            <span class="font-medium dark:text-white">{{ $billFile->name }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-300">@lang('admin::app.bill-files.view.sponsor')</span>
            <span class="font-medium dark:text-white">{{ $billFile->sponsor }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-300">@lang('admin::app.bill-files.view.status')</span>
            <span class="font-medium dark:text-white">{{ $billFile->status }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-300">@lang('admin::app.bill-files.view.session')</span>
            <span class="font-medium dark:text-white">{{ $billFile->session }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-300">@lang('admin::app.bill-files.view.year')</span>
            <span class="font-medium dark:text-white">{{ $billFile->year }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-300">@lang('admin::app.bill-files.view.is_tracked')</span>
            <span class="font-medium dark:text-white">
                {{ $billFile->is_tracked ? __('admin::app.common.yes') : __('admin::app.common.no') }}
            </span>
        </div>
    </div>

    {!! view_render_event('admin.bill-files.view.attributes.form_controls.before', ['billFile' => $billFile]) !!}

    <x-admin::form
        v-slot="{ meta, errors, handleSubmit }"
        as="div"
        ref="modalForm"
    >
        <form @submit="handleSubmit($event, () => {})">
            {!! view_render_event('admin.bill-files.view.attributes.form_controls.attributes_view.before', ['billFile' => $billFile]) !!}

            <x-admin::attributes.view
                :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                    'entity_type' => 'bill_files',
                    ['code', 'NOTIN', ['billid', 'name', 'sponsor', 'status', 'session', 'year', 'is_tracked']]
                ])"
                :entity="$billFile"
                :url="route('admin.bill-files.update', $billFile->id)"
                :allow-edit="true"
            />

            {!! view_render_event('admin.bill-files.view.attributes.form_controls.attributes_view.after', ['billFile' => $billFile]) !!}
        </form>
    </x-admin::form>

    {!! view_render_event('admin.bill-files.view.attributes.form_controls.after', ['billFile' => $billFile]) !!}
</div>

{!! view_render_event('admin.bill-files.view.attributes.after', ['billFile' => $billFile]) !!}
