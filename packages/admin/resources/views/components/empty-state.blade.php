@props([
    'title',
    'content',
    'button' => false,
    'permission' => false,
    'url' => false,
])

<div {{ $attributes->twMerge(['class' => 'relative w-full lg:flex lg:items-center py-12 lg:py-16']) }}>
    <div class="w-full lg:w-1/2 relative flex justify-center lg:block">
        {{ $slot }}
    </div>

    <div class="mt-10 w-full lg:mt-0 lg:w-1/2 relative lg:py-20 flex items-center justify-center">
        <div class="w-full text-center sm:max-w-md lg:text-left">
            <h3 class="text-lg leading-6 sm:text-lg lg:text-2xl sm:leading-7 font-medium text-gray-900 dark:text-white font-heading">
                {{ $title }}
            </h3>
            <p class="mt-4 text-gray-500 dark:text-gray-400 text-base leading-6">
                {{ $content }}
            </p>

            @if($permission)
                @can($permission)
                    @if($button && $url)
                        <x-shopper::buttons.primary :link="$url" class="mt-5">
                            {{ $button }}
                        </x-shopper::buttons.primary>
                    @endif
                @endcan
            @endif
        </div>
    </div>
</div>
