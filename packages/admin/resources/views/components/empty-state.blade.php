@props([
    'title',
    'content',
    'button' => false,
    'permission' => false,
    'url' => false,
    'panel' => null,
])

<div
    {{ $attributes->twMerge(['class' => 'relative w-full lg:flex lg:items-center py-8 lg:py-12']) }}
>
    <div class="relative flex w-full justify-center lg:block lg:w-1/2">
        {{ $slot }}
    </div>

    <div class="relative mt-10 flex w-full items-center justify-center lg:mt-0 lg:w-1/2 lg:py-20">
        <div class="w-full text-center sm:max-w-md lg:text-left">
            <h3
                class="font-heading text-lg font-medium text-gray-900 sm:text-lg sm:leading-7 lg:text-2xl dark:text-white"
            >
                {{ $title }}
            </h3>
            <p class="mt-4 text-base text-gray-500 dark:text-gray-400">
                {{ $content }}
            </p>

            @if ($permission)
                @can($permission)
                    @if ($url)
                        <x-filament::button tag="a" :href="$url" class="mt-5">
                            {{ $button }}
                        </x-filament::button>
                    @elseif ($panel)
                        <x-filament::button
                            type="button"
                            wire:click="$dispatch('openPanel', {{ $panel }})"
                            class="mt-5"
                        >
                            {{ $button }}
                        </x-filament::button>
                    @endif
                @endcan
            @endif
        </div>
    </div>
</div>
