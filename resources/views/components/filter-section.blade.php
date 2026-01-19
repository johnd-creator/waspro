@props([
    'action' => null,
    'method' => 'GET',
    'showReset' => false,
    'resetRoute' => null,
    'submitButtonText' => 'Filter',
    'submitButtonIcon' => 'fas fa-filter',
    'submitButtonTextClass' => 'bg-blue-600 hover:bg-blue-700',
    'gridColumns' => 'grid-cols-1 md:grid-cols-3'
])

@csrf

<form @if($action) action="{{ $action }}" @endif method="{{ $method }}" class="grid grid-cols-1 gap-4 {{ $gridColumns }}">
    {{ $slot }}
    
    @isset($actions)
        {{ $actions }}
    @else
        <div class="flex gap-3">
            <button type="submit"
                    class="rounded-xl {{ $submitButtonTextClass }} px-6 py-3 font-medium text-white transition-colors">
                <i class="{{ $submitButtonIcon }} mr-2"></i>{{ $submitButtonText }}
            </button>
            @if($showReset && $resetRoute && request()->hasAny(array_keys(request()->query())))
                <a href="{{ $resetRoute }}"
                   class="rounded-xl px-6 py-3 font-medium transition-colors"
                   style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">
                    <i class="fas fa-times mr-2"></i>Reset
                </a>
            @endif
        </div>
    @endisset
</form>
