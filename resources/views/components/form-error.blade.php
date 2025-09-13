@props(['field' => null, 'message' => null])

@if($field && $errors->has($field))
    <div class="text-red-600 text-sm mt-1 font-medium">
        {{ $errors->first($field) }}
    </div>
@elseif($message)
    <div class="text-red-600 text-sm mt-1 font-medium">
        {{ $message }}
    </div>
@endif