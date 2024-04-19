@props([
    'title',
    'content',
    'button' => false,
    'permission' => false,
    'url' => false,
    'panel' => null,
])

<div {{ $attributes->twMerge(['class' => 'relative w-full lg:flex lg:items-center py-10 lg:py-12']) }}>
    <div class="relative flex w-full justify-center lg:block lg:w-1/2">
        {{ $slot }}
    </div>

    <div class="relative mt-10 flex w-full items-center justify-center lg:mt-0 lg:w-1/2 lg:py-20">
        <div class="w-full text-center sm:max-w-md lg:text-left">
            <h3
                class="font-heading text-lg font-medium leading-6 text-gray-900 dark:text-white sm:text-lg sm:leading-7 lg:text-2xl"
            >
                {{ $title }}
            </h3>
            <p class="mt-4 text-base leading-6 text-gray-500 dark:text-gray-400">
                {{ $content }}
            </p>

            @if ($permission)
                @can($permission)
                    @if ($url)
                        <x-shopper::buttons.primary :link="$url" class="mt-5">
                            {{ $button }}
                        </x-shopper::buttons.primary>
                    @elseif ($panel)
                        <x-shopper::buttons.primary
                            type="button"
                            wire:click="$dispatch('openPanel', {{ $panel }})"
                            class="mt-5"
                        >
                            {{ $button }}
                        </x-shopper::buttons.primary>
                    @endif
                @endcan
            @endif
        </div>
    </div>
</div>
