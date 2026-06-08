<div class="space-y-3">
    <div class="flex items-center space-x-4">
        @if (filled($icon))
            <x-dynamic-component
                :component="$icon"
                class="size-6 text-gray-400 dark:text-gray-500"
                stroke-width="1"
                aria-hidden="true"
            />
        @endif

        <span class="text-primary-600 dark:text-primary-500 text-sm font-medium">
            {{ $stepLabel }}
        </span>
    </div>
    <h2 class="font-heading text-2xl font-medium text-gray-900 dark:text-white">
        {{ $title }}
        @if (filled($optional))
            <span class="font-normal text-gray-500 dark:text-gray-400">
                ({{ $optional }})
            </span>
        @endif
    </h2>

    @if (filled($description))
        <p class="text-sm leading-6 text-gray-500 lg:max-w-2xl dark:text-gray-300">
            {{ $description }}
        </p>
    @endif
</div>
