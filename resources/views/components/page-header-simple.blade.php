@props([
    'title' => '',
    'subtitle' => '',
    'createRoute' => null,
    'createButtonText' => 'Tambah Data',
    'createButtonIcon' => 'fas fa-plus-circle'
])

<div class="mb-6 rounded-2xl border shadow-sm"
     style="background-color: var(--card-bg); border-color: var(--border-primary);">
    <div class="flex items-center justify-between border-b px-6 py-6"
         style="border-color: var(--border-primary);">
        <div>
            <h1 class="mb-2 text-2xl font-bold" style="color: var(--text-primary);">
                {{ $title }}
            </h1>
            @if($subtitle)
                <p style="color: var(--text-secondary);">
                    {{ $subtitle }}
                </p>
            @endif
        </div>
        @if($createRoute)
            <a href="{{ $createRoute }}"
               class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white shadow-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700">
                <i class="{{ $createButtonIcon }} mr-2"></i>
                <span>{{ $createButtonText }}</span>
            </a>
        @endif
    </div>
</div>
