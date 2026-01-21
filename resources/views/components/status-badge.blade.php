@props([
    'status' => null,
    'label' => null,
    'size' => 'md',
    'showIcon' => true,
    'icon' => null
])
@php
    $statusConfig = [
        'true' => [
            'class' => 'bg-green-100 text-green-800 border-green-200',
            'icon' => 'fas fa-check-circle',
            'label' => 'Aktif'
        ],
        'false' => [
            'class' => 'bg-gray-100 text-gray-800 border-gray-200',
            'icon' => 'fas fa-times-circle',
            'label' => 'Tidak Aktif'
        ],
        'active' => [
            'class' => 'bg-green-100 text-green-800 border-green-200',
            'icon' => 'fas fa-check-circle',
            'label' => 'Aktif'
        ],
        'inactive' => [
            'class' => 'bg-gray-100 text-gray-800 border-gray-200',
            'icon' => 'fas fa-times-circle',
            'label' => 'Tidak Aktif'
        ],
        'is_active' => [
            'class' => 'bg-green-100 text-green-800 border-green-200',
            'icon' => 'fas fa-check-circle',
            'label' => 'Aktif'
        ],
        'status_aktif' => [
            'class' => 'bg-green-100 text-green-800 border-green-200',
            'icon' => 'fas fa-check-circle',
            'label' => 'Aktif'
        ],
        'aktif' => [
            'class' => 'bg-green-100 text-green-800 border-green-200',
            'icon' => 'fas fa-check-circle',
            'label' => 'Aktif'
        ],
        'tersimpan' => [
            'class' => 'bg-blue-100 text-blue-800 border-blue-200',
            'icon' => 'fas fa-box',
            'label' => 'Tersimpan'
        ],
        'diangkut' => [
            'class' => 'bg-green-100 text-green-800 border-green-200',
            'icon' => 'fas fa-truck',
            'label' => 'Diangkut'
        ],
        'kadaluarsa' => [
            'class' => 'bg-red-100 text-red-800 border-red-200',
            'icon' => 'fas fa-exclamation-triangle',
            'label' => 'Kadaluarsa'
        ],
        'expired' => [
            'class' => 'bg-red-100 text-red-800 border-red-200',
            'icon' => 'fas fa-exclamation-triangle',
            'label' => 'Kadaluarsa'
        ],
        '1' => [
            'class' => 'bg-green-100 text-green-800 border-green-200',
            'icon' => 'fas fa-check-circle',
            'label' => 'Aktif'
        ],
        '0' => [
            'class' => 'bg-gray-100 text-gray-800 border-gray-200',
            'icon' => 'fas fa-times-circle',
            'label' => 'Tidak Aktif'
        ],
    ];

    if ($status instanceof \BackedEnum) {
        $status = $status->value;
    }

    $statusValue = is_bool($status) ? ($status ? 'true' : 'false') : (string) $status;

    if (is_bool($status)) {
        $statusValue = $status ? 'true' : 'false';
    } elseif (is_numeric($status)) {
        $statusValue = $status == 1 ? '1' : '0';
    } else {
        $statusValue = strtolower((string) $status);
    }

    $config = $statusConfig[$statusValue] ?? $statusConfig['inactive'];
    $displayLabel = $label ?? $config['label'];
    $displayIcon = $icon ?? $config['icon'];

    $sizeClasses = [
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-2 text-base'
    ];

    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<span class="inline-flex items-center {{ $sizeClass }} font-medium rounded-full border {{ $config['class'] }}">
    @if($showIcon)
        <i class="{{ $displayIcon }} mr-1"></i>
    @endif
    {{ $displayLabel }}
</span>
