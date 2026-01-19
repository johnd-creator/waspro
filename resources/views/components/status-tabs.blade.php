@props([
    'tabs' => [],
    'baseRoute' => null,
    'activeTab' => null,
    'queryParam' => 'search_status',
    'preserveQuery' => []
])

@php
    $preserveQuery = array_merge($preserveQuery, request()->query());
    unset($preserveQuery['page']);
    unset($preserveQuery[$queryParam]);
@endphp

<div class="flex flex-wrap gap-2">
    @foreach($tabs as $key => $tab)
        @php
            $isActive = ($activeTab === null && $key === '') || ($activeTab === $key);
            $tabLabel = is_array($tab) ? ($tab['label'] ?? $key) : $tab;
            
            if (is_array($tab)) {
                $activeClass = $tab['activeClass'] ?? 'tab-active';
                $inactiveClass = $tab['inactiveClass'] ?? 'tab-inactive';
            } else {
                $activeClass = 'tab-active';
                $inactiveClass = 'tab-inactive';
            }
            
            $currentClass = $isActive ? $activeClass : $inactiveClass;
        @endphp

        <a href="{!! $baseRoute ? route($baseRoute, array_merge($preserveQuery, [$queryParam => $key === '' ? '' : $key])) : request()->fullUrlWithQuery([$queryParam => $key === '' ? '' : $key]) !!}"
           class="inline-flex items-center rounded-full px-4 py-2 text-sm font-medium transition-colors {{ $isActive ? 'ring-2 ring-blue-500' : '' }} {{ $currentClass }}">
            {{ $tabLabel }}
        </a>
    @endforeach
</div>

<style>
    .tab-active {
        background-color: var(--accent-bg);
        color: var(--accent-primary);
    }
    
    .tab-inactive {
        background-color: var(--card-secondary-bg);
        color: var(--text-secondary);
    }
</style>
