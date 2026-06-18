@props([
    'title',
    'content',
    'button' => false,
    'permission' => false,
    'url' => false,
    'panel' => null,
])

<div
    {{ $attributes->twMerge(['class' => 'relative mx-auto flex w-full max-w-md flex-col items-center py-12 text-center']) }}
>
    <div class="flex justify-center">
        {{ $slot }}
    </div>

    <h3 class="font-heading mt-8 text-xl font-semibold text-sh-fg">
        {{ $title }}
    </h3>
    <p class="mt-2 max-w-sm text-base text-sh-fg-muted">
        {{ $content }}
    </p>

    @if ($permission)
        @can($permission)
            @if ($url)
                <x-filament::button tag="a" :href="$url" wire:navigate class="mt-6">
                    {{ $button }}
                </x-filament::button>
            @elseif ($panel)
                <x-filament::button
                    type="button"
                    wire:click="$dispatch('openPanel', {{ $panel }})"
                    class="mt-6"
                >
                    {{ $button }}
                </x-filament::button>
            @endif
        @endcan
    @endif
</div>
