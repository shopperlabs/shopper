<x-shopper::container class="space-y-8 pt-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
        <div class="space-y-2">
            <x-shopper::heading>
                <x-slot name="title">
                    <div class="flex items-center flex-wrap gap-2">
                        <h2 class="font-heading text-2xl font-bold text-sh-fg sm:truncate sm:text-3xl">
                            {{ $campaign->name }}
                        </h2>
                        <x-filament::badge :color="$campaign->status->getColor()" :icon="$campaign->status->getIcon()">
                            {{ $campaign->status->getLabel() }}
                        </x-filament::badge>
                    </div>
                </x-slot>
            </x-shopper::heading>
            <p class="text-sh-fg-secondary max-w-2xl text-sm">
                {{ __('shopper::pages/campaigns.edit.description') }}
            </p>
        </div>

        <div class="flex shrink-0 items-center gap-2">
            {{ $this->deleteAction }}
        </div>
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

        <aside class="mt-6 min-w-0 space-y-4 lg:sticky lg:top-4 lg:col-span-1 lg:mt-0 lg:self-start">
            @include(
                'shopper::livewire.pages.campaigns.partials.summary-panel',
                ['summary' => $this->summary, 'campaign' => $campaign]
            )

            @include('shopper::livewire.pages.campaigns.partials.budget-panel', ['campaign' => $campaign])

            @include('shopper::livewire.pages.campaigns.partials.promotions-panel', ['campaign' => $campaign])
        </aside>
    </div>

    <x-filament-actions::modals />
</x-shopper::container>
