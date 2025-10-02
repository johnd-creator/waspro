@props([
    'title' => '',
    'value' => '0',
    'icon' => 'fas fa-chart-bar',
    'color' => 'blue',
    'subtitle' => '',
    'delay' => '0'
])

@php
    $colorClasses = [
        'red' => [
            'shadow' => 'shadow-red-500/10 hover:shadow-red-500/20',
            'gradient' => 'from-red-500 to-red-600',
            'text' => 'text-red-600'
        ],
        'amber' => [
            'shadow' => 'shadow-amber-500/10 hover:shadow-amber-500/20',
            'gradient' => 'from-amber-500 to-amber-600',
            'text' => 'text-amber-600'
        ],
        'blue' => [
            'shadow' => 'shadow-blue-500/10 hover:shadow-blue-500/20',
            'gradient' => 'from-blue-500 to-blue-600',
            'text' => 'text-blue-600'
        ],
        'emerald' => [
            'shadow' => 'shadow-emerald-500/10 hover:shadow-emerald-500/20',
            'gradient' => 'from-emerald-500 to-emerald-600',
            'text' => 'text-emerald-600'
        ],
        'purple' => [
            'shadow' => 'shadow-purple-500/10 hover:shadow-purple-500/20',
            'gradient' => 'from-purple-500 to-purple-600',
            'text' => 'text-purple-600'
        ]
    ];
    
    $selectedColor = $colorClasses[$color] ?? $colorClasses['blue'];
@endphp

<x-theme-card 
    class="group p-4 {{ $selectedColor['shadow'] }} hover:shadow-xl transition-all duration-300 hover:-translate-y-1" 
    data-aos="fade-up" 
    data-aos-delay="{{ $delay }}"
>
    <div class="flex items-center justify-between mb-3">
        <div class="p-3 bg-gradient-to-br {{ $selectedColor['gradient'] }} rounded-lg shadow-md group-hover:scale-105 transition-transform duration-300">
            <i class="{{ $icon }} text-lg text-white"></i>
        </div>
        <div class="text-right">
            <div style="color: var(--text-primary);" class="text-2xl font-bold" id="{{ $color }}-count">{{ $value }}</div>
            <div style="color: var(--text-secondary);" class="font-medium text-sm">{{ $title }}</div>
        </div>
    </div>
    @if($subtitle)
    <div class="flex items-center gap-1 {{ $selectedColor['text'] }}">
        <i class="{{ $icon }} text-xs"></i>
        <span class="text-xs font-semibold">{{ $subtitle }}</span>
    </div>
    @endif
</x-theme-card>