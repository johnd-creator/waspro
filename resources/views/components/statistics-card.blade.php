@props([
    'title',
    'value',
    'icon',
    'type' => 'default', // expired, critical, warning, safe, info, success
    'subtitle' => null,
    'url' => null,
    'size' => 'md' // sm, md, lg
])

@php
    $typeConfig = [
        'expired' => [
            'bg' => 'bg-red-500',
            'text' => 'text-white',
            'hover' => 'hover:bg-red-600',
            'shadow' => 'shadow-red-500/25'
        ],
        'critical' => [
            'bg' => 'bg-orange-500',
            'text' => 'text-white',
            'hover' => 'hover:bg-orange-600',
            'shadow' => 'shadow-orange-500/25'
        ],
        'warning' => [
            'bg' => 'bg-yellow-500',
            'text' => 'text-white',
            'hover' => 'hover:bg-yellow-600',
            'shadow' => 'shadow-yellow-500/25'
        ],
        'safe' => [
            'bg' => 'bg-green-500',
            'text' => 'text-white',
            'hover' => 'hover:bg-green-600',
            'shadow' => 'shadow-green-500/25'
        ],
        'info' => [
            'bg' => 'bg-blue-500',
            'text' => 'text-white',
            'hover' => 'hover:bg-blue-600',
            'shadow' => 'shadow-blue-500/25'
        ],
        'success' => [
            'bg' => 'bg-emerald-500',
            'text' => 'text-white',
            'hover' => 'hover:bg-emerald-600',
            'shadow' => 'shadow-emerald-500/25'
        ],
        'default' => [
            'bg' => 'bg-slate-500',
            'text' => 'text-white',
            'hover' => 'hover:bg-slate-600',
            'shadow' => 'shadow-slate-500/25'
        ]
    ];
    
    $config = $typeConfig[$type] ?? $typeConfig['default'];
    
    $sizeConfig = [
        'sm' => [
            'padding' => 'p-4',
            'title' => 'text-2xl',
            'subtitle' => 'text-sm',
            'icon' => 'text-2xl'
        ],
        'md' => [
            'padding' => 'p-6',
            'title' => 'text-3xl',
            'subtitle' => 'text-base',
            'icon' => 'text-3xl'
        ],
        'lg' => [
            'padding' => 'p-8',
            'title' => 'text-4xl',
            'subtitle' => 'text-lg',
            'icon' => 'text-4xl'
        ]
    ];
    
    $sizeClasses = $sizeConfig[$size] ?? $sizeConfig['md'];
@endphp

@if($url)
    <a href="{{ $url }}" class="block">
@endif

<div class="{{ $config['bg'] }} {{ $config['text'] }} rounded-2xl {{ $sizeClasses['padding'] }} shadow-lg {{ $config['shadow'] }} transition-all duration-200 {{ $url ? $config['hover'] . ' hover:shadow-xl hover:scale-105 cursor-pointer' : '' }}">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <div class="{{ $sizeClasses['title'] }} font-bold mb-1">{{ $value }}</div>
            <div class="{{ $sizeClasses['subtitle'] }} opacity-90">{{ $title }}</div>
            @if($subtitle)
                <div class="text-sm opacity-75 mt-1">{{ $subtitle }}</div>
            @endif
        </div>
        <div class="ml-4">
            <i class="{{ $icon }} {{ $sizeClasses['icon'] }} opacity-80"></i>
        </div>
    </div>
</div>

@if($url)
    </a>
@endif