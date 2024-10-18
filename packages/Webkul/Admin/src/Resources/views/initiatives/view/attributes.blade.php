{!! view_render_event('admin.initiatives.view.attributes.before', ['initiative' => $initiative]) !!}

<div class="dark: flex w-full flex-col gap-4 border-b border-gray-200 p-4 dark:border-gray-800">
    <h4 class="flex items-center justify-between font-semibold dark:text-white">
        @lang('admin::app.initiatives.view.attributes.title')

        @if (bouncer()->hasPermission('initiatives.edit'))
            <a
                href="{{ route('admin.initiatives.edit', $initiative->id) }}"
                class="icon-edit rounded-md p-1 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                target="_blank"
            ></a>
        @endif
    </h4>

    {!! view_render_event('admin.initiatives.view.attributes.form_controls.before', ['initiative' => $initiative]) !!}

    <x-admin::form
        v-slot="{ meta, errors, handleSubmit }"
        as="div"
        ref="modalForm"
    >
        <form @submit="handleSubmit($event, () => {})">
            {!! view_render_event('admin.initiatives.view.attributes.form_controls.attributes.view.before', ['initiative' => $initiative]) !!}

            <x-admin::attributes.view
                :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                    'entity_type' => 'initiatives',
                    ['code', 'NOTIN', ['title', 'description', 'initiative_pipeline_id', 'initiative_pipeline_stage_id']]
                ])"
                :entity="$initiative"
                :url="route('admin.initiatives.attributes.update', $initiative->id)"
                :allow-edit="true"
            />

            {!! view_render_event('admin.initiatives.view.attributes.form_controls.attributes.view.after', ['initiative' => $initiative]) !!}
        </form>
    </x-admin::form>

    {!! view_render_event('admin.initiatives.view.attributes.form_controls.after', ['initiative' => $initiative]) !!}
</div>

{!! view_render_event('admin.initiatives.view.attributes.before', ['initiative' => $initiative]) !!}
