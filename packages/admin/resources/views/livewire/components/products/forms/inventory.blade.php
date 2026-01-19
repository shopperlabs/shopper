<x-shopper::container>
    <form wire:submit="store">
        {{ $this->form }}

        <div class="mt-8 grid grid-cols-1 gap-x-6 md:grid-cols-3 md:gap-x-12">
            <div class="flex justify-end md:col-span-2 md:col-start-2 lg:max-w-3xl">
                <x-filament::button type="submit" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                    {{ __('shopper::forms.actions.update') }}
                </x-filament::button>
            </div>
        </div>
    </form>

    <x-shopper::separator />

    <section
        class="fi-section fi-aside grid grid-cols-1 items-start gap-x-6 gap-y-4 lg:grid-cols-3 lg:gap-x-12 lg:gap-y-6"
    >
        <x-shopper::section-heading
            class="grid flex-1 gap-y-1"
            :title="__('shopper::pages/products.inventory.stock_title')"
            :description="__('shopper::pages/products.inventory.stock_description')"
        />
        <div class="lg:col-span-2 lg:max-w-3xl">
            {{ $this->table }}
        </div>
    </section>
</x-shopper::container>
