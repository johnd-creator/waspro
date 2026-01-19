@props([
    'name' => 'search',
    'value' => null,
    'placeholder' => 'Cari...',
    'icon' => 'fas fa-search',
    'formAction' => null,
    'formMethod' => 'GET',
    'showReset' => false,
    'resetRoute' => null
])

<form @if($formAction) action="{{ $formAction }}" @endif method="{{ $formMethod }}" class="relative">
    <div class="relative">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
            <i class="{{ $icon }}" style="color: var(--text-tertiary);"></i>
        </div>
        <input type="text"
               name="{{ $name }}"
               value="{{ old($name, $value) }}"
               placeholder="{{ $placeholder }}"
               class="w-full rounded-xl border py-3 pl-12 pr-4 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
               style="background-color: var(--input-bg); border-color: var(--border-primary); color: var(--input-text);">
    </div>
    @if($showReset && $resetRoute)
        @if(request()->hasAny([$name]))
            <a href="{{ $resetRoute }}"
               class="mt-2 inline-flex items-center rounded-xl px-6 py-2 text-sm font-medium transition-colors"
               style="background-color: var(--card-secondary-bg); color: var(--text-secondary);">
                <i class="fas fa-times mr-2"></i>
                Reset
            </a>
        @endif
    @endif
</form>
