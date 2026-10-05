@props([
    'icon',
    'theme',
])

@php
    $label = __('shopper::forms.actions.theme_switcher', ['label' => $theme]);
@endphp

<button
    aria-label="{{ $label }}"
    type="button"
    x-on:click="
        theme = @js($theme)
        dropdownOpen = false
    "
    class="fi-theme-switcher-btn hover:bg-sh-muted flex items-center justify-center gap-2 rounded-md px-1.5 py-2 transition duration-75 outline-none"
    x-bind:class="
        theme === @js($theme)
            ? 'fi-active bg-sh-muted text-sh-fg'
            : 'text-sh-fg-muted hover:text-sh-fg-secondary focus-visible:text-sh-fg-secondary'
    "
>
    <x-filament::icon
        :alias="'shopper::theme-switcher.' . $theme . '-button'"
        :icon="$icon"
        class="size-5"
        aria-hidden="true"
    />
    <span class="text-sh-fg-muted text-xs font-medium capitalize">{{ $label }}</span>
</button>
