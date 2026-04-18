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
    class="fi-theme-switcher-btn flex items-center justify-center gap-2 rounded-md py-2 px-1.5 transition duration-75 outline-none hover:bg-gray-100 dark:hover:bg-white/10"
    x-bind:class="
        theme === @js($theme)
            ? 'fi-active bg-zinc-100 text-zinc-700 dark:bg-white/5 dark:text-zinc-400'
            : 'text-zinc-400 hover:text-zinc-500 focus-visible:text-zinc-500 dark:text-zinc-500 dark:hover:text-zinc-400'
    "
>
    <x-filament::icon :alias="'shopper::theme-switcher.' . $theme . '-button'" :icon="$icon" class="size-5" aria-hidden="true" />
    <span class="text-xs capitalize text-zinc-500 dark:text-zinc-400 font-medium">{{ $label }}</span>
</button>
