@props([
    'items' => null,
    'paginator' => null,
    'showEmptyState' => true,
    'emptyStateIcon' => 'fas fa-folder-open',
    'emptyStateTitle' => 'Belum ada data',
    'emptyStateDescription' => 'Silakan tambahkan data baru',
    'emptyStateActionText' => null,
    'emptyStateActionRoute' => null,
    'emptyStateActionIcon' => 'fas fa-plus',
    'colspan' => 8,
    'showPagination' => true
])

<div class="overflow-hidden rounded-2xl border shadow-sm"
     style="background-color: var(--card-bg); border-color: var(--border-primary);">
    <div class="overflow-x-auto">
        <table class="min-w-full w-full">
            {{ $header }}
            
            @if($items || $paginator)
                <tbody class="divide-y" style="border-color: var(--border-primary);">
                    @forelse(($items ?? $paginator ?? null) as $item)
                        {{ $row($item) }}
                    @empty
                        @if($showEmptyState)
                            <x-empty-state
                                :icon="$emptyStateIcon"
                                :title="$emptyStateTitle"
                                :description="$emptyStateDescription"
                                :action-text="$emptyStateActionText"
                                :action-route="$emptyStateActionRoute"
                                :action-icon="$emptyStateActionIcon" 
                                :colspan="$colspan" />
                        @endif
                    @endforelse
                </tbody>
            @endif
        </table>
    </div>
    
    @if($showPagination && ($items ? $items->hasPages() : ($paginator ? $paginator->hasPages() : false)))
        <div class="border-t p-4" style="border-color: var(--border-primary); background-color: var(--card-bg);">
            {{ ($items ?? $paginator)->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@isset($footer)
    {{ $footer }}
@endisset
