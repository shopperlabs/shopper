@props([
    'attribute',
    'activated' => false,
])

<div
    class="flex flex-col rounded-xl bg-gray-100 p-1 ring-1 ring-inset ring-gray-100/50 dark:bg-white/10 dark:ring-white/10"
>
    <header class="flex items-center justify-between gap-2 px-2 py-2.5">
        <div class="flex items-center gap-2">
            @if ($attribute->icon)
                <x-dynamic-component
                    :component="$attribute->icon"
                    class="size-5 text-gray-400 dark:text-gray-500"
                    aria-hidden="true"
                />
            @endif

            <h5 class="text-sm font-medium leading-5 text-gray-950 dark:text-white">
                {{ $attribute->name }}
            </h5>
            @if ($activated)
                <x-filament::badge size="sm" color="success" icon="untitledui-check-verified">
                    {{ __('shopper::forms.actions.enable') }}
                </x-filament::badge>
            @endif
        </div>

        @isset($action)
            {{ $action }}
        @endisset
    </header>
    <div class="mt-1.5 flex-1 rounded-lg bg-white px-2 py-3 ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
        {{ $slot }}
    </div>
</div>
