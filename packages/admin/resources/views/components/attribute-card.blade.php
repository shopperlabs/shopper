@props(['attribute', 'activated' => false])

<div class="flex flex-col rounded-xl bg-gray-100 ring-1 ring-gray-100/50 ring-inset p-1 dark:bg-white/10 dark:ring-white/10">
    <header class="flex items-center justify-between px-2 py-2.5 gap-2">
        <div class="flex items-center gap-2">
            @if($attribute->icon)
                <x-dynamic-component
                    :component="$attribute->icon"
                    class="h-5 w-5 text-gray-400 dark:text-gray-500"
                    aria-hidden="true"
                />
            @endif
            <h5 class="text-sm leading-5 font-medium text-gray-950 dark:text-white">
                {{ $attribute->name }}
            </h5>
            @if($activated)
                <x-filament::badge
                    size="sm"
                    color="success"
                    icon="untitledui-check-verified"
                >
                    {{ __('shopper::layout.forms.actions.enabled') }}
                </x-filament::badge>
            @endif
        </div>

        @isset($action)
            {{ $action }}
        @endisset
    </header>
    <div class="mt-1.5 flex-1 bg-white ring-1 ring-gray-200 px-2 py-3 rounded-lg dark:bg-gray-900 dark:ring-white/10">
        {{ $slot }}
    </div>
</div>
