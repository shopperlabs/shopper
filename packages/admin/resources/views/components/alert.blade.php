@php
    use Filament\Support\Enums\IconSize;
@endphp

@props([
    'message',
    'color' => 'warning',
    'title' => null,
    'icon' => null,
    'iconSize' => IconSize::Medium,
])

@php
    if (! $iconSize instanceof IconSize) {
        $iconSize = filled($iconSize) ? (IconSize::tryFrom($iconSize) ?? $iconSize) : null;
    }

    $containerClass = \Illuminate\Support\Arr::toCssClasses([
        'rounded-lg p-4 ring-1',
        match ($color) {
            'info' => 'bg-info-50 ring-info-200 dark:ring-info-400/20 dark:bg-info-800/30',
            'danger' => 'bg-danger-50 ring-danger-200 dark:ring-danger-400/20 dark:bg-danger-800/30',
            'success' => 'bg-success-50 ring-success-200 dark:ring-success-400/20 dark:bg-success-800/30',
            'warning' => 'bg-warning-50 ring-warning-200 dark:ring-warning-400/20 dark:bg-warning-800/30',
            default => 'bg-custom-50 ring-custom-200 dark:ring-custom-400/20 dark:bg-custom-800/30',
        },
    ]);

    $iconClasses = \Illuminate\Support\Arr::toCssClasses([
        'sh-icon size-4',
        match ($iconSize) {
            IconSize::Small => 'size-4',
            IconSize::Medium => 'size-5',
            IconSize::Large => 'size-6',
            default => $iconSize,
        },
        match ($color) {
            'info' => 'text-info-400 dark:text-info-500',
            'danger' => 'text-danger-400 dark:text-danger-500',
            'success' => 'text-success-400 dark:text-success-500',
            'warning' => 'text-warning-400 dark:text-warning-500',
            default => 'text-custom-400 dark:text-custom-500',
        },
    ]);

    $titleClasses = \Illuminate\Support\Arr::toCssClasses([
        'text-sm/5 font-medium',
        match ($color) {
            'info' => 'text-info-600 dark:text-info-500',
            'danger' => 'text-danger-600 dark:text-danger-500',
            'success' => 'text-success-600 dark:text-success-500',
            'warning' => 'text-warning-600 dark:text-warning-500',
            default => 'text-custom-600 dark:text-custom-500',
        },
    ]);

    $messageClasses = \Illuminate\Support\Arr::toCssClasses([
        'text-sm/5',
        'mt-2' => $title,
        match ($color) {
            'info' => 'text-info-500 dark:text-info-400',
            'danger' => 'text-danger-500 dark:text-danger-400',
            'success' => 'text-success-500 dark:text-success-400',
            'warning' => 'text-warning-500 dark:text-warning-400',
            default => 'text-custom-500 dark:text-custom-400',
        },
    ]);
@endphp

<div {{ $attributes->twMerge(['class' => $containerClass]) }}>
    <div class="flex gap-3">
        @if($icon)
            <div class="shrink-0">
                <x-filament::icon
                    :attributes="
                        \Filament\Support\prepare_inherited_attributes(
                            new \Illuminate\View\ComponentAttributeBag([
                                'icon' => $icon,
                            ])
                        )
                            ->class([$iconClasses])
                    "
                />
            </div>
        @endif

        <div>
            @if($title)
                <h3 @class([$titleClasses])>{{ $title }}</h3>
            @endif

            <p @class([$messageClasses])>
                {{ $message }}
            </p>
        </div>
    </div>
</div>
