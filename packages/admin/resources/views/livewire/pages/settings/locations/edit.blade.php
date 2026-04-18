<x-shopper::container>
    <x-shopper::heading class="my-6" :title="$inventory->name" />

    <livewire:shopper-settings.locations.form :$inventory />
</x-shopper::container>
