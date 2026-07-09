<div>
    <form wire:submit="store">
        {{ $this->form }}

        <div class="mt-10 flex items-center justify-between">
            <div>
                @if ($this->useAsThumbnailAction->isVisible())
                    {{ $this->useAsThumbnailAction }}
                @endif
            </div>

            <x-filament::button type="submit" wire.loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" />
                {{ __('shopper::forms.actions.update') }}
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
