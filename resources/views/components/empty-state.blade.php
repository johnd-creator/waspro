@props([
    'icon' => 'fas fa-folder-open',
    'title' => 'Belum ada data',
    'description' => 'Silakan tambahkan data baru',
    'actionText' => null,
    'actionRoute' => null,
    'actionIcon' => 'fas fa-plus'
])

<tr>
    <td class="px-6 py-12 text-center">
        <div class="flex flex-col items-center justify-center">
            <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full"
                 style="background-color: var(--hover-bg);">
                <i class="{{ $icon }} text-4xl"
                   style="color: var(--text-tertiary);"></i>
            </div>
            <h3 class="mb-2 text-lg font-medium"
                style="color: var(--text-primary);">
                {{ $title }}
            </h3>
            <p class="mb-4 text-sm"
               style="color: var(--text-secondary);">
                {{ $description }}
            </p>
            @if($actionText && $actionRoute)
                <a href="{{ $actionRoute }}"
                   class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 font-medium text-white transition-colors hover:bg-blue-700">
                    <i class="{{ $actionIcon }} mr-2"></i>
                    {{ $actionText }}
                </a>
            @endif
        </div>
    </td>
</tr>
