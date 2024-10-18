{!! view_render_event('admin.bills.view.attributes.before', ['bill' => $bill]) !!}

<div class="flex w-full flex-col gap-4 border-b border-gray-200 p-4 dark:border-gray-800">
    <h4 class="font-semibold dark:text-white">
        @lang('admin::app.bills.view.details')
    </h4>

    <div class="flex flex-col gap-2">
        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-300">@lang('admin::app.bills.view.billid')</span>
            <span class="font-medium dark:text-white">{{ $bill->billid }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-300">@lang('admin::app.bills.view.name')</span>
            <span class="font-medium dark:text-white">{{ $bill->name }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-300">@lang('admin::app.bills.view.status')</span>
            <span class="font-medium dark:text-white">{{ $bill->status }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-300">@lang('admin::app.bills.view.session')</span>
            <span class="font-medium dark:text-white">{{ $bill->session }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-300">@lang('admin::app.bills.view.year')</span>
            <span class="font-medium dark:text-white">{{ $bill->year }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-600 dark:text-gray-300">@lang('admin::app.bills.view.is_tracked')</span>
            <span class="font-medium dark:text-white">
                {{ $bill->is_tracked ? __('admin::app.common.yes') : __('admin::app.common.no') }}
            </span>
        </div>
    </div>

    {!! view_render_event('admin.bills.view.attributes.form_controls.before', ['bill' => $bill]) !!}

    <x-admin::form
        v-slot="{ meta, errors, handleSubmit }"
        as="div"
        ref="modalForm"
    >
        <form @submit="handleSubmit($event, () => {})">
            {!! view_render_event('admin.bills.view.attributes.form_controls.attributes_view.before', ['bill' => $bill]) !!}

            <x-admin::attributes.view
                :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                    'entity_type' => 'bills',
                    ['code', 'NOTIN', ['billid', 'name', 'status', 'session', 'year', 'is_tracked']]
                ])"
                :entity="$bill"
                :url="route('admin.bills.update', $bill->id)"
                :allow-edit="true"
            />

            {!! view_render_event('admin.bills.view.attributes.form_controls.attributes_view.after', ['bill' => $bill]) !!}
        </form>
    </x-admin::form>

    {!! view_render_event('admin.bills.view.attributes.form_controls.after', ['bill' => $bill]) !!}
</div>

{!! view_render_event('admin.bills.view.attributes.after', ['bill' => $bill]) !!}

