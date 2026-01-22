@props([
    'setting',
])

<x-shopper::link
    :href="$setting->url() ?? '#'"
    class="flex items-start space-x-4 rounded-lg p-2 hover:bg-gray-50 dark:hover:bg-white/5"
>
    <div class="bg-primary-600 flex size-10 shrink-0 items-center justify-center rounded-lg text-white">
        <x-filament::icon :icon="$setting->icon()" />
    </div>
    <div class="space-y-1">
        <h5 class="inline-flex items-center gap-3 font-medium text-gray-900 dark:text-white">
            {{ $setting->name() }}

            @if (! $setting->url())
                <x-filament::badge size="sm" color="primary">
                    {{ __('shopper::words.soon') }}
                </x-filament::badge>
            @endif
        </h5>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ $setting->description() }}
        </p>
    </div>
</x-shopper::link>
