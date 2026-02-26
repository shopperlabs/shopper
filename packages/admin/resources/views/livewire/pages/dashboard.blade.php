<x-shopper::container class="py-12">
    @if ($this->showSetupGuide)
        <livewire:shopper-setup-guide />
    @else
        <div class="space-y-8">
            <livewire:shopper-dashboard.stat-cards />
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <livewire:shopper-dashboard.revenue-chart />
                </div>
                <div>
                    <livewire:shopper-dashboard.top-selling-products />
                </div>
            </div>

            <livewire:shopper-dashboard.recent-orders />
        </div>
   @endif
</x-shopper::container>
