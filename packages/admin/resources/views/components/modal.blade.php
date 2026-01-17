@props([
    'formAction' => false,
    'headerClasses' => '',
    'contentClasses' => 'p-4 sm:p-6',
    'footerClasses' => 'p-4 sm:flex sm:p-6',
])

<div {{ $attributes->twMerge(['class' => 'h-full']) }}>
    @if ($formAction)
        {{-- format-ignore-start --}}<form wire:submit="{{ $formAction }}">{{-- format-ignore-end --}}
    @endif

    <div class="{{ $headerClasses }}">
        @if (isset($title))
            <h3 class="font-heading flex items-center text-lg font-semibold text-gray-900 lg:text-xl dark:text-white">
                {{ $title }}
            </h3>
        @endif

        @if (isset($subtitle))
            <p class="mt-2 text-sm leading-5 text-gray-500 dark:text-gray-400">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    @if (isset($content))
        <div class="{{ $contentClasses }}">
            {{ $content }}
        </div>
    @endif

    <div class="{{ $footerClasses }}">
        {{ $buttons ?? null }}
    </div>

    @if ($formAction)
        {{-- format-ignore-start --}}</form>{{-- format-ignore-end --}}
    @endif
</div>
