@props([
    'title',
    'content',
    'button',
    'permission',
    'url',
])

<div class="relative w-full md:flex md:items-center py-12 lg:py-16">
    <div class="w-full md:w-1/2 relative flex justify-center md:block">
        {{ $slot }}
    </div>

    <div class="mt-10 w-full md:mt-0 md:w-1/2 relative lg:py-20 flex items-center justify-center">
        <div class="w-full text-center sm:max-w-md md:text-left">
            <h3 class="text-lg leading-6 sm:text-lg lg:text-2xl sm:leading-7 font-medium text-gray-800">{{ $title }}</h3>
            <p class="mt-4 text-gray-500 text-base leading-6">{{ $content }}</p>
            @can($permission)
                <a href="{{ $url }}" class="mt-5 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                    {{ $button }}
                </a>
            @endcan
        </div>
    </div>
</div>
