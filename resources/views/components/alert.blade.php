@props([
    'type' => 'info',
    'message' => null,
    'dismissible' => true,
    'icon' => null
])

@php
    $alertClasses = 'px-4 py-3 rounded-xl mb-6 flex items-start gap-3';
    $iconClasses = 'flex-shrink-0 w-5 h-5 mt-0.5';
    
    switch($type) {
        case 'success':
            $alertClasses .= ' bg-green-50 border border-green-200 text-green-800';
            $defaultIcon = 'fas fa-check-circle';
            break;
        case 'error':
        case 'danger':
            $alertClasses .= ' bg-red-50 border border-red-200 text-red-800';
            $defaultIcon = 'fas fa-exclamation-triangle';
            break;
        case 'warning':
            $alertClasses .= ' bg-yellow-50 border border-yellow-200 text-yellow-800';
            $defaultIcon = 'fas fa-exclamation-triangle';
            break;
        case 'info':
        default:
            $alertClasses .= ' bg-blue-50 border border-blue-200 text-blue-800';
            $defaultIcon = 'fas fa-info-circle';
            break;
    }
    
    $iconToShow = $icon ?: $defaultIcon;
@endphp

@if($message || $slot->isNotEmpty())
<div class="{{ $alertClasses }}" role="alert">
    @if($iconToShow)
        <i class="{{ $iconToShow }} {{ $iconClasses }}"></i>
    @endif
    
    <div class="flex-1">
        @if($message)
            {{ $message }}
        @else
            {{ $slot }}
        @endif
    </div>
    
    @if($dismissible)
        <button type="button" class="flex-shrink-0 ml-2 text-current opacity-50 hover:opacity-75 transition-opacity" 
                onclick="this.parentElement.style.display='none'">
            <i class="fas fa-times w-4 h-4"></i>
        </button>
    @endif
</div>
@endif