@props([
    'action',
    'title',
    'description' => null,
])

<x-shopper::slideover-card>
    <form wire:submit="{{ $action }}" class="flex h-full flex-col">
        <header class="p-4">
            <div class="flex items-start justify-between">
                <h2 class="text-sh-fg text-lg font-medium">
                    {{ $title }}
                </h2>
                <x-livewire-slide-over::close-icon />
            </div>

            @if ($description)
                <div class="mt-1">
                    <p class="text-sh-fg-muted text-sm">
                        {{ $description }}
                    </p>
                </div>
            @endif
        </header>
        <div class="h-0 flex-1 overflow-y-auto px-4 pt-2 pb-6">
            {{ $slot }}
        </div>
        <div class="border-t border-sh-border flex shrink-0 justify-end gap-3 p-4">
            <x-filament::button
                color="gray"
                wire:click="$dispatch('closePanel')"
                type="button"
                class="mt-3 sm:mt-0 sm:w-auto"
            >
                {{ __('shopper::forms.actions.cancel') }}
            </x-filament::button>
            <x-filament::button type="submit" wire.loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="{{ $action }}" class="text-white" />
                {{ __('shopper::forms.actions.save') }}
            </x-filament::button>
        </div>
    </form>
</x-shopper::slideover-card>
