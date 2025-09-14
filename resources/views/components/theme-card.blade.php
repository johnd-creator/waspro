@props([
    'class' => '',
    'style' => '',
    'gradient' => false,
    'hover' => true,
    'shadow' => 'lg',
    'rounded' => 'xl',
    'backdrop' => true
])

@php
    $baseClasses = 'border transition-all duration-300';
    
    // Add backdrop blur if enabled
    if ($backdrop) {
        $baseClasses .= ' backdrop-blur-xl';
    }
    
    // Add hover effects if enabled
    if ($hover) {
        $baseClasses .= ' hover:shadow-2xl hover:shadow-slate-900/10 hover:-translate-y-1';
    }
    
    // Add shadow classes
    $shadowClasses = [
        'sm' => 'shadow-sm',
        'md' => 'shadow-md', 
        'lg' => 'shadow-lg shadow-slate-900/5',
        'xl' => 'shadow-xl shadow-slate-900/5',
        '2xl' => 'shadow-2xl shadow-slate-900/10'
    ];
    $baseClasses .= ' ' . ($shadowClasses[$shadow] ?? $shadowClasses['lg']);
    
    // Add rounded classes
    $roundedClasses = [
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg', 
        'xl' => 'rounded-xl',
        '2xl' => 'rounded-2xl'
    ];
    $baseClasses .= ' ' . ($roundedClasses[$rounded] ?? $roundedClasses['xl']);
    
    // Combine with custom classes
    $allClasses = trim($baseClasses . ' ' . $class);
    
    // Base styles with CSS variables
    $baseStyles = 'background-color: var(--card-bg); border-color: var(--border-primary);';
    
    // Add gradient if enabled
    if ($gradient) {
        $baseStyles = 'background: linear-gradient(135deg, var(--gradient-start, #dbeafe) 0%, var(--gradient-end, #e0e7ff) 100%); border-color: var(--border-primary);';
    }
    
    /* Combine with custom styles */
    $allStyles = trim($baseStyles . ' ' . $style);
@endphp

<div class="{{ $allClasses }}" style="{{ $allStyles }}" {{ $attributes }}>
    {{ $slot }}
</div>