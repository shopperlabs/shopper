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
            <h3
                class="flex items-center font-heading text-lg font-semibold leading-6 text-gray-900 dark:text-white lg:text-xl"
            >
                {{ $title }}
            </h3>
        @endif

        @if (isset($subtitle))
            <div class="mt-2">
                <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                    {{ $subtitle }}
                </p>
            </div>
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
