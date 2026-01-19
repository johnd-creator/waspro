@props([
    'data' => null,
    'columns' => [],
    'showPagination' => true
])

<div class="overflow-hidden rounded-2xl border shadow-sm"
     style="background-color: var(--card-bg); border-color: var(--border-primary);">
    <div class="overflow-x-auto">
        <table class="min-w-full w-full">
            {{ $header }}
        </table>
    </div>

    @if($data && $data->hasPages() && $showPagination)
        <div class="border-t px-6 py-4"
             style="border-color: var(--border-primary); background-color: var(--card-bg);">
            {{ $data->links() }}
        </div>
    @endif
</div>
