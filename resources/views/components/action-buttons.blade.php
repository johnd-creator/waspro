@props([
    'showView' => true,
    'showEdit' => true,
    'showDelete' => true,
    'viewRoute' => null,
    'editRoute' => null,
    'deleteRoute' => null,
    'deleteMessage' => 'Apakah Anda yakin ingin menghapus data ini?',
    'deleteTitle' => 'Konfirmasi Hapus',
    'deleteButtonText' => 'Ya, Hapus',
    'deleteButtonColor' => '#dc2626',
    'deleteCancelButtonColor' => '#6b7280',
    'itemTitle' => null
])

<div class="flex items-center space-x-1">
    @if($showView && $viewRoute)
        <a href="{{ $viewRoute }}"
           class="btn-action-base btn-action-view"
           title="Lihat Detail{{ $itemTitle ? ' (' . $itemTitle . ')' : '' }}"
           target="_self">
            <i class="fas fa-eye text-sm"></i>
        </a>
    @endif

    @if($showEdit && $editRoute)
        <a href="{{ $editRoute }}"
           class="btn-action-base btn-action-edit"
           title="Edit{{ $itemTitle ? ' (' . $itemTitle . ')' : '' }}">
            <i class="fas fa-edit text-sm"></i>
        </a>
    @endif

    @if($showDelete && $deleteRoute)
        <form action="{{ $deleteRoute }}"
              method="POST"
              class="inline delete-form"
              data-title="{{ $deleteTitle }}"
              data-message="{{ $deleteMessage }}"
              data-button-text="{{ $deleteButtonText }}"
              data-button-color="{{ $deleteButtonColor }}"
              data-cancel-color="{{ $deleteCancelButtonColor }}">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="btn-action-base btn-action-delete"
                    title="Hapus{{ $itemTitle ? ' (' . $itemTitle . ')' : '' }}">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </form>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForms = document.querySelectorAll('.delete-form');

        deleteForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const title = form.dataset.title || 'Konfirmasi Hapus';
                const message = form.dataset.message || 'Apakah Anda yakin ingin menghapus data ini?';
                const buttonText = form.dataset.buttonText || 'Ya, Hapus';
                const buttonColor = form.dataset.buttonColor || '#dc2626';
                const cancelColor = form.dataset.cancelColor || '#6b7280';

                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: title,
                        text: message,
                        icon: 'warning',
                        iconColor: '#f59e0b',
                        showCancelButton: true,
                        confirmButtonColor: buttonColor,
                        cancelButtonColor: cancelColor,
                        confirmButtonText: buttonText,
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-xl',
                            icon: 'border-0'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                } else {
                    if (confirm(message)) {
                        form.submit();
                    }
                }
            });
        });
    });
</script>
@endpush
