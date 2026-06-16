<x-shopper::slideover-card class="divide-y divide-sh-border">
    <header class="p-4">
        <div class="flex items-start justify-between">
            <h2 class="text-lg font-medium text-sh-fg">
                {{ __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/discounts.single')]) }}
            </h2>
            <x-livewire-slide-over::close-icon />
        </div>
    </header>

    <form wire:submit="store" class="h-0 flex-1 overflow-y-auto [&>div]:h-full">
        {{ shopper()->getRenderHook(\Shopper\View\SalesRenderHook::DISCOUNT_CREATE_FORM_BEFORE) }}

        {{ $this->form }}

        {{ shopper()->getRenderHook(\Shopper\View\SalesRenderHook::DISCOUNT_CREATE_FORM_AFTER) }}
    </form>
</x-shopper::slideover-card>
