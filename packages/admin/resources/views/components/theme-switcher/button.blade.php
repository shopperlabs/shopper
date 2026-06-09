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
    x-on:click="theme = @js($theme); dropdownOpen = false"
    class="fi-theme-switcher-btn flex items-center justify-center gap-2 rounded-md py-2 px-1.5 transition duration-75 outline-none hover:bg-sh-muted"
    x-bind:class="
        theme === @js($theme)
            ? 'fi-active bg-sh-muted text-sh-fg'
            : 'text-sh-fg-muted hover:text-sh-fg-secondary focus-visible:text-sh-fg-secondary'
    "
>
    <x-filament::icon :alias="'shopper::theme-switcher.' . $theme . '-button'" :icon="$icon" class="size-5" aria-hidden="true" />
    <span class="text-xs capitalize text-sh-fg-muted font-medium">{{ $label }}</span>
</button>
