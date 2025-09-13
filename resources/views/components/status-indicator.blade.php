@props([
    'status',
    'label' => null,
    'size' => 'md', // sm, md, lg
    'showIcon' => true,
    'class' => ''
])

@php
    $statusConfig = [
        'expired' => [
            'class' => 'bg-red-100 text-red-800 border-red-200',
            'icon' => 'fas fa-times-circle',
            'label' => 'Kadaluarsa'
        ],
        'critical' => [
            'class' => 'bg-orange-100 text-orange-800 border-orange-200',
            'icon' => 'fas fa-exclamation-triangle',
            'label' => 'Kritis'
        ],
        'warning' => [
            'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            'icon' => 'fas fa-exclamation-circle',
            'label' => 'Peringatan'
        ],
        'safe' => [
            'class' => 'bg-green-100 text-green-800 border-green-200',
            'icon' => 'fas fa-check-circle',
            'label' => 'Aman'
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
        'pending' => [
            'class' => 'bg-blue-100 text-blue-800 border-blue-200',
            'icon' => 'fas fa-clock',
            'label' => 'Menunggu'
        ],
        'success' => [
            'class' => 'bg-green-100 text-green-800 border-green-200',
            'icon' => 'fas fa-check',
            'label' => 'Berhasil'
        ],
        'error' => [
            'class' => 'bg-red-100 text-red-800 border-red-200',
            'icon' => 'fas fa-times',
            'label' => 'Error'
        ]
    ];
    
    $config = $statusConfig[$status] ?? $statusConfig['pending'];
    $displayLabel = $label ?? $config['label'];
    
    $sizeClasses = [
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-2 text-base'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<span class="inline-flex items-center {{ $sizeClass }} font-medium rounded-full border {{ $config['class'] }} {{ $class }}">
    @if($showIcon)
        <i class="{{ $config['icon'] }} mr-1"></i>
    @endif
    {{ $displayLabel }}
</span>