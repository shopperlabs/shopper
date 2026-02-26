<x-shopper::container class="py-12">
    @if ($this->showSetupGuide)
        <livewire:shopper-setup-guide />
    @else
        <div></div>
   @endif
</x-shopper::container>
