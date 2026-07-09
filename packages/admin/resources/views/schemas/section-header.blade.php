@php
    use Filament\Support\Enums\IconSize;
    use Filament\Support\View\Components\IconComponent;

    $heading = $getHeading();
    $description = $getDescription();
    $icon = $getIcon();
@endphp

<section class="fi-section fi-section-not-contained">
    <header class="fi-section-header">
        {{
            \Filament\Support\generate_icon_html($icon, attributes: (new \Illuminate\View\ComponentAttributeBag)
                ->color(IconComponent::class, $getIconColor()), size: $getIconSize() ?? IconSize::Large)
        }}

        <div class="fi-section-header-text-ctn">
            <h2 class="fi-section-header-heading">
                {{ $heading }}
            </h2>

            @if (filled((string) $description))
                <p class="fi-section-header-description">
                    {{ $description }}
                </p>
            @endif
        </div>
    </header>
</section>
