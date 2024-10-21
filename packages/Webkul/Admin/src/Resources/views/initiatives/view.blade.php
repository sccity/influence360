<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.initiatives.view.title', ['title' => $initiative->title])
    </x-slot>

    <!-- Content -->
    <div class="relative flex gap-4">
        <!-- Left Panel -->
        {!! view_render_event('admin.initiatives.view.left.before', ['initiative' => $initiative]) !!}

        <div class="sticky top-[73px] flex min-w-[394px] max-w-[394px] flex-col self-start rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- Initiative Information -->
            <div class="flex w-full flex-col gap-2 border-b border-gray-200 p-4 dark:border-gray-800">
                <!-- Breadcrums -->
                <div class="flex items-center justify-between">
                    <x-admin::breadcrumbs
                        name="initiatives.view"
                        :entity="$initiative"
                    />
                </div>

                <div class="mb-2">
                    @if (($days = $initiative->rotten_days) > 0)
                        @php
                            $initiative->tags->prepend([
                                'name'  => '<span class="icon-rotten text-base"></span>' . trans('admin::app.initiatives.view.rotten-days', ['days' => $days]),
                                'color' => '#FEE2E2'
                            ]);
                        @endphp
                    @endif

                    {!! view_render_event('admin.initiatives.view.tags.before', ['initiative' => $initiative]) !!}

                    <!-- Tags -->
                    <x-admin::tags
                        :attach-endpoint="route('admin.initiatives.tags.attach', $initiative->id)"
                        :detach-endpoint="route('admin.initiatives.tags.detach', $initiative->id)"
                        :added-tags="$initiative->tags"
                    />

                    {!! view_render_event('admin.initiatives.view.tags.after', ['initiative' => $initiative]) !!}
                </div>


                {!! view_render_event('admin.initiatives.view.title.before', ['initiative' => $initiative]) !!}

                <!-- Title -->
                <h3 class="text-lg font-bold dark:text-white">
                    {{ $initiative->title }}
                </h1>

                {!! view_render_event('admin.initiatives.view.title.after', ['initiative' => $initiative]) !!}

                <!-- Activity Actions -->
                <div class="flex flex-wrap gap-2">
                    {!! view_render_event('admin.initiatives.view.actions.before', ['initiative' => $initiative]) !!}

                    @if (bouncer()->hasPermission('mail.compose'))
                        <!-- Mail Activity Action -->
                        <x-admin::activities.mail-activity
                            :entity="$initiative"
                            entity-control-name="initiative_id"
                        />
                    @endif

                    @if (bouncer()->hasPermission('activities.create'))
                        <!-- File Activity Action -->
                        <x-admin::activities.actions.file
                            :entity="$initiative"
                            entity-control-name="initiative_id"
                        />

                        <!-- Note Activity Action -->
                        <x-admin::activities.actions.note
                            :entity="$initiative"
                            entity-control-name="initiative_id"
                        />

                        <!-- Activity Action -->
                        <x-admin::activities.actions.activity
                            :entity="$initiative"
                            entity-control-name="initiative_id"
                        />
                    @endif

                    {!! view_render_event('admin.initiatives.view.actions.after', ['initiative' => $initiative]) !!}
                </div>
            </div>

            <!-- Initiative Attributes -->
            @include ('admin::initiatives.view.attributes')

            <!-- Contact Person -->
            @include ('admin::initiatives.view.person')
        </div>

        {!! view_render_event('admin.initiatives.view.left.after', ['initiative' => $initiative]) !!}

        {!! view_render_event('admin.initiatives.view.right.before', ['initiative' => $initiative]) !!}

        <!-- Right Panel -->
        <div class="flex w-full flex-col gap-4 rounded-lg">
            <!-- Stages Navigation -->
            @include ('admin::initiatives.view.stages')

            <!-- Activities -->
            {!! view_render_event('admin.initiatives.view.activities.before', ['initiative' => $initiative]) !!}

            <x-admin::activities
                :endpoint="route('admin.initiatives.activities.index', $initiative->id)"
                :email-detach-endpoint="route('admin.initiatives.emails.detach', $initiative->id)"
                :extra-types="[
                    ['name' => 'description', 'label' => trans('admin::app.initiatives.view.tabs.description')],
                ]"
                @refresh-activities="$refs.activities.get()"
            >
                <!-- Description -->
                <x-slot:description>
                    <div class="p-4 dark:text-white">
                        {{ $initiative->description }}
                    </div>
                </x-slot>
            </x-admin::activities>

            {!! view_render_event('admin.initiatives.view.activities.after', ['initiative' => $initiative]) !!}
        </div>

        {!! view_render_event('admin.initiatives.view.right.after', ['initiative' => $initiative]) !!}
    </div>
</x-admin::layouts>
