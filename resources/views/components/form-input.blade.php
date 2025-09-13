@props([
    'name',
    'label' => null,
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'maxlength' => null,
    'minlength' => null,
    'rows' => 3,
    'class' => ''
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    @if($type === 'textarea')
        <textarea 
            id="{{ $name }}"
            name="{{ $name }}"
            placeholder="{{ $placeholder }}"
            rows="{{ $rows }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            @if($maxlength) maxlength="{{ $maxlength }}" @endif
            @if($minlength) minlength="{{ $minlength }}" @endif
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-vertical {{ $errors->has($name) ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }} {{ $class }}"
        >{{ old($name, $value) }}</textarea>
    @else
        <input 
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($readonly) readonly @endif
            @if($maxlength) maxlength="{{ $maxlength }}" @endif
            @if($minlength) minlength="{{ $minlength }}" @endif
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors {{ $errors->has($name) ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '' }} {{ $class }}"
        >
    @endif
    
    <x-form-error :name="$name" />
</div>