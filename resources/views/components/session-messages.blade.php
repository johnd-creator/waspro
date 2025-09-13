<!-- Success Messages -->
@if(session('success'))
    <x-alert type="success" :message="session('success')" />
@endif

<!-- Error Messages -->
@if(session('error'))
    <x-alert type="error" :message="session('error')" />
@endif

<!-- Warning Messages -->
@if(session('warning'))
    <x-alert type="warning" :message="session('warning')" />
@endif

<!-- Info Messages -->
@if(session('info'))
    <x-alert type="info" :message="session('info')" />
@endif

<!-- Validation Errors -->
@if($errors->any())
    <x-alert type="error">
        <div class="font-semibold mb-2">Terdapat kesalahan pada form:</div>
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif