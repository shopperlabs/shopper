<div class="space-y-3">
    <div class="flex items-center space-x-4">
        @if (filled($icon))
            <x-dynamic-component
                :component="$icon"
                class="size-6 text-sh-fg-muted"
                stroke-width="1"
                aria-hidden="true"
            />
        @endif

        <span class="text-primary-600 dark:text-primary-500 text-sm font-medium">
            {{ $stepLabel }}
        </span>
    </div>
    <h2 class="font-heading text-2xl font-medium text-sh-fg">
        {{ $title }}
        @if (filled($optional))
            <span class="font-normal text-sh-fg-muted">
                ({{ $optional }})
            </span>
        @endif
    </h2>

    @if (filled($description))
        <p class="text-sm leading-6 text-sh-fg-muted lg:max-w-2xl">
            {{ $description }}
        </p>
    @endif
</div>
