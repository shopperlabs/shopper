<x-shopper::container class="space-y-8 pt-5">
    <div class="space-y-2">
        <x-shopper::heading
            :title="__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/campaigns.single')])"
        />
        <p class="text-sh-fg-secondary max-w-2xl text-sm">
            {{ __('shopper::pages/campaigns.create.description') }}
        </p>
    </div>

    <div class="lg:grid lg:grid-cols-3 lg:items-start lg:gap-6">
        <form wire:submit="save" class="min-w-0 lg:col-span-2">
            {{ $this->form }}

            <div class="mt-10 border-t border-sh-border pt-8 flex justify-end gap-3">
                <x-filament::button
                    color="gray"
                    tag="a"
                    :href="route('shopper.campaigns.index')"
                    wire:navigate
                >
                    {{ __('shopper::forms.actions.cancel') }}
                </x-filament::button>
                <x-filament::button type="submit" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="save" class="text-white" />
                    {{ __('shopper::forms.actions.save') }}
                </x-filament::button>
            </div>
        </form>

        <aside class="mt-6 min-w-0 lg:sticky lg:top-4 lg:col-span-1 lg:mt-0 lg:self-start">
            @include(
                'shopper::livewire.pages.campaigns.partials.summary-panel',
                ['summary' => $this->summary, 'campaign' => null]
            )
        </aside>
    </div>

    <x-filament-actions::modals />
</x-shopper::container>
