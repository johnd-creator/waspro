@props([
    'title' => '',
    'subtitle' => '',
    'icon' => 'fas fa-home',
    'breadcrumbs' => [],
    'status' => null,
    'statusText' => 'Aktif',
    'gradient' => 'from-blue-600 to-purple-600'
])

<div class="mb-6">
    <div class="container-fluid">
        <x-theme-card 
            class="p-6 hover:shadow-2xl hover:shadow-slate-900/10" 
            rounded="2xl" 
            data-aos="fade-up"
        >
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-gradient-to-br {{ $gradient }} rounded-2xl flex items-center justify-center text-white mr-4 shadow-xl">
                        <i class="{{ $icon }} text-2xl"></i>
                    </div>
                    <div>
                        <h1 style="color: var(--text-primary);" class="text-3xl font-bold mb-1 tracking-tight">{{ $title }}</h1>
                        @if($breadcrumbs || $subtitle)
                        <div class="flex items-center gap-2 text-sm">
                            @if($breadcrumbs)
                                @foreach($breadcrumbs as $index => $breadcrumb)
                                    @if($index > 0)
                                        <i class="fas fa-chevron-right text-xs" style="color: var(--text-secondary);"></i>
                                    @endif
                                    @if(isset($breadcrumb['url']))
                                        <a href="{{ $breadcrumb['url'] }}" style="color: var(--text-secondary);" class="hover:text-blue-600 transition-colors duration-200 font-medium">
                                            @if(isset($breadcrumb['icon']))
                                                <i class="{{ $breadcrumb['icon'] }} mr-1"></i>
                                            @endif
                                            {{ $breadcrumb['text'] }}
                                        </a>
                                    @else
                                        <span style="color: var(--text-primary);" class="font-semibold">{{ $breadcrumb['text'] }}</span>
                                    @endif
                                @endforeach
                            @else
                                <span style="color: var(--text-secondary);" class="font-medium">{{ $subtitle }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @if($status)
                <div class="text-right">
                    <div style="color: var(--text-secondary);" class="text-sm font-medium">{{ $status }}</div>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span style="color: var(--text-primary);" class="text-lg font-semibold">{{ $statusText }}</span>
                    </div>
                </div>
                @endif
            </div>
        </x-theme-card>
    </div>
</div>