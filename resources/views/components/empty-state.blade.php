@props([
    'title',
    'content',
    'button' => false,
    'permission' => false,
    'url' => false,
])

<div {{ $attributes->merge(['class' => 'relative w-full md:flex md:items-center py-12 lg:py-16']) }}>
    <div class="w-full md:w-1/2 relative flex justify-center md:block">
        {{ $slot }}
    </div>

    <div class="mt-10 w-full md:mt-0 md:w-1/2 relative lg:py-20 flex items-center justify-center">
        <div class="w-full text-center sm:max-w-md md:text-left">
            <h3 class="text-lg leading-6 sm:text-lg lg:text-2xl sm:leading-7 font-medium text-secondary-900 dark:text-white">{{ $title }}</h3>
            <p class="mt-4 text-secondary-500 dark:text-secondary-400 text-base leading-6">{{ $content }}</p>
            @if($permission)
                @can($permission)
                    @if($button && $url)
                        <x-shopper::button :link="$url" class="mt-5">
                            {{ $button }}
                        </x-shopper::button>
                    @endif
                @endcan
            @endif
        </div>
    </div>
</div>
