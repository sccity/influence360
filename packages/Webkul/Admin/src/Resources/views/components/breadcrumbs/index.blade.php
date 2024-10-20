@props([
    'name'   => '',
    'entity' => null,
    'route'  => null,
])

<div class="flex justify-start max-lg:hidden">
    <div class="flex items-center gap-x-3.5">        
        @if($route)
            {{ Breadcrumbs::view('admin::partials.breadcrumbs', $name, $route, $entity) }}
        @elseif($entity)
            {{ Breadcrumbs::view('admin::partials.breadcrumbs', $name, $entity) }}
        @else
            {{ Breadcrumbs::view('admin::partials.breadcrumbs', $name) }}
        @endif
    </div>
</div>
