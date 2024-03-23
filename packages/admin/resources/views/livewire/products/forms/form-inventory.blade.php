<x-shopper::container>
    <div>
        <div>
            <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white font-heading">
                {{ __('shopper::pages/products.inventory.title') }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-secondary-500 dark:text-secondary-400">
                {{ __('shopper::pages/products.inventory.description') }}
            </p>
        </div>
        <x-shopper::card class="mt-5 overflow-hidden">
            <div class="p-4">
                <div class="grid gap-4 sm:grid-cols-3 sm:gap-5">
                    <x-shopper::forms.group class="sm:col-span-1" :label="__('shopper::layout.forms.label.sku')" for="sku" :error="$errors->first('sku')">
                        <x-shopper::forms.input wire:model.defer="sku" id="sku" type="text" autocomplete="off" />
                    </x-shopper::forms.group>
                    <div class="sm:col-span-1">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.barcode')" for="barcode" :error="$errors->first('barcode')">
                            <x-shopper::forms.input wire:model.defer="barcode" id="barcode" type="text" autocomplete="off" />
                        </x-shopper::forms.group>
                        @if($barcodeImage)
                            <div class="mt-2 rounded-sm w-auto shrink-0">
                                {!! $barcodeImage !!}
                            </div>
                        @endif
                    </div>
                    <x-shopper::forms.group class="sm:col-span-1" :label="__('shopper::layout.forms.label.safety_stock')" for="security_stock">
                        <x-shopper::forms.input wire:model.defer="securityStock" id="security_stock" type="number" min="0" autocomplete="off" />
                    </x-shopper::forms.group>
                </div>
            </div>
            <div class="px-4 py-3 text-right">
                <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                    <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                    {{ __('shopper::layout.forms.actions.update') }}
                </x-shopper::buttons.primary>
            </div>
        </x-shopper::card>
    </div>

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div>
            <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">
                {{ __('shopper::pages/products.inventory.stock_title') }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-secondary-500 dark:text-secondary-400">
                {{ __('shopper::pages/products.inventory.stock_description') }}
            </p>
        </div>
    </div>

    <x-shopper::card class="mt-5 overflow-hidden">
        @if($inventories->count() > 1)
            <div class="p-4 sm:p-5 relative flex items-center justify-between border-b border-secondary-200 dark:border-secondary-700">
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border-r-0 border border-secondary-300 dark:border-secondary-700 bg-white dark:bg-secondary-700">
                        <x-untitledui-marker-pin class="h-5 w-5 text-secondary-500 dark:text-secondary-400" />
                    </span>
                    <x-shopper::forms.select wire:model.lazy="inventory" id="inventory" class="-ml-px block w-full pl-3 pr-9 py-2 rounded-l-none rounded-r-md" aria-label="{{ __('shopper::layout.forms.placeholder.select_inventory') }}">
                        @foreach($inventories as $inventory)
                            <option value="{{ $inventory->id }}">{{ $inventory->name }}</option>
                        @endforeach
                    </x-shopper::forms.select>
                </span>
                <div class="relative z-0 inline-flex items-center leading-5 text-secondary-700 dark:text-secondary-400">
                    <span class="block text-sm font-medium mr-4">{{ __('shopper::pages/products.quantity_available') }}</span>
                    <x-shopper::stock-badge :stock="$product->stock" />
                </div>
            </div>
        @endif
        <div class="sm:flex sm:items-center sm:justify-between p-4 sm:px-5">
            <div class="relative z-0 inline-flex items-center leading-5 text-secondary-700 dark:text-secondary-400">
                <span class="block text-sm font-medium mr-4">
                    {{ __('shopper::pages/products.current_qty_inventory') }}
                </span>
                <x-shopper::stock-badge :stock="$currentStock" />
            </div>
            <div class="mt-5 sm:mt-0 sm:ml-4">
                <div class="lg:flex lg:items-center">
                    <div class="flex items-center lg:pr-4">
                        <p class="text-sm font-medium text-secondary-500 dark:text-secondary-400 mr-4">
                            {{ $realStock }}
                        </p>
                        <div>
                            <div class="flex rounded-md shadow-sm">
                                <div class="relative flex items-stretch grow focus-within:z-10">
                                    <x-shopper::forms.input
                                        wire:model.debounche.550ms="value"
                                        type="number"
                                        aria-label="{{ __('shopper::layout.forms.label.stock_number_value') }}"
                                        id="stockValue"
                                        step="1"
                                        min="0"
                                        class="block w-32 rounded-r-none rounded-l-md"
                                        placeholder="12"
                                    />
                                </div>
                                <button wire:click="decrementStock" type="button" class="-ml-px relative inline-flex items-center px-4 py-2 rounded-none border border-secondary-300 dark:border-secondary-600 bg-white dark:bg-secondary-700 text-sm font-medium text-secondary-500 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-secondary-600 focus:z-10 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                                    <span>&minus;</span>
                                </button>
                                <button wire:click="incrementStock" type="button" class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-secondary-300 dark:border-secondary-600 bg-white dark:bg-secondary-700 text-sm font-medium text-secondary-500 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-secondary-600 focus:z-10 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                                    <span>&plus;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 lg:mt-0 flex items-center">
                        <x-shopper::buttons.primary wire:click="updateCurrentStock" wire.loading.attr="disabled" type="button">
                            <x-shopper::loader wire:loading wire:target="updateCurrentStock" class="text-white" />
                            {{ __('shopper::layout.forms.actions.update') }}
                        </x-shopper::buttons.primary>
                        @if($histories->isNotEmpty())
                            <div class="flex items-center pl-4 space-x-4">
                                <x-shopper::buttons.default wire:click="export" type="button">
                                    <x-untitledui-download-cloud-01 class="w-5 h-5 mr-2" />
                                    {{ __('shopper::layout.forms.actions.export') }}
                                </x-shopper::buttons.default>
                            </div>
                        @endif
                    </div>
                </div>
                @error('value')
                    <p class="mt-2 text-sm text-danger-500">
                        {{ __('shopper::layout.forms.validation.integer') }}
                    </p>
                @enderror
            </div>
        </div>
        @if($histories->isEmpty())
            <div class="flex flex-col items-center justify-center p-4 sm:py-5 space-y-2">
                <span class="shrink-0">
                    <x-untitledui-file-05 class="h-8 w-8 text-primary-500" />
                </span>
                <h3 class="text-center font-medium text-secondary-500 dark:text-secondary-400 text-lg">
                    {{ __('shopper::pages/products.inventory.empty') }}
                </h3>
            </div>
        @else
            <div class="flex flex-col border-t border-secondary-200 dark:border-secondary-700">
                <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                    <div class="align-middle inline-block min-w-full overflow-hidden">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-secondary-200 dark:border-secondary-700 bg-secondary-50 dark:bg-secondary-700">
                                    <x-shopper::tables.table-head>
                                        {{ __('shopper::words.date') }}
                                    </x-shopper::tables.table-head>
                                    <x-shopper::tables.table-head>
                                        {{ __('shopper::words.event') }}
                                    </x-shopper::tables.table-head>
                                    <x-shopper::tables.table-head>
                                        {{ __('shopper::words.location') }}
                                    </x-shopper::tables.table-head>
                                    <x-shopper::tables.table-head class="text-right">
                                        {{ __('shopper::words.adjustment') }}
                                    </x-shopper::tables.table-head>
                                    <x-shopper::tables.table-head class="text-right">
                                        {{ __('shopper::pages/products.inventory.movement') }}
                                    </x-shopper::tables.table-head>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-secondary-100 dark:divide-secondary-700">
                                @foreach($histories as $inventoryHistory)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                            {{ $inventoryHistory->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                            {{ __($inventoryHistory->event) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                            {{ $inventoryHistory->inventory->name }}
                                        </td>
                                        <td @class([
                                            'px-6 py-4 whitespace-no-wrap text-sm leading-5 text-right',
                                            'text-green-500' => $inventoryHistory->old_quantity > 0,
                                            'text-red-500' => $inventoryHistory->old_quantity <= 0,
                                        ])>
                                            {{ $inventoryHistory->adjustment }}
                                        </td>
                                        <td @class([
                                            'px-6 py-4 whitespace-no-wrap text-sm leading-5 text-right',
                                            'text-secondary-500 dark:text-secondary-400' => $inventoryHistory->quantity > 0,
                                            'text-red-500' => $inventoryHistory->quantity <= 0,
                                        ])>
                                            {{ $inventoryHistory->quantity }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="px-4 py-3 flex items-center justify-between border-t border-secondary-200 dark:border-secondary-700 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        {{ $histories->links('shopper::livewire.wire-mobile-pagination-links') }}
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm leading-5 text-secondary-700 dark:text-secondary-400">
                                {{ __('shopper::words.showing') }}
                                <span class="font-medium">{{ ($histories->currentPage() - 1) * $histories->perPage() + 1 }}</span>
                                {{ __('shopper::words.to') }}
                                <span class="font-medium">{{ ($histories->currentPage() - 1) * $histories->perPage() + count($histories->items()) }}</span>
                                {{ __('shopper::words.of') }}
                                <span class="font-medium"> {!! $histories->total() !!}</span>
                                {{ __('shopper::words.results') }}
                            </p>
                        </div>
                        {{ $histories->links() }}
                    </div>
                </div>
            </div>
        @endif
    </x-shopper::card>
</x-shopper::container>
