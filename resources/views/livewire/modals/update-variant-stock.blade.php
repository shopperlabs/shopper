<x-shopper::modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-secondary-200 dark:border-secondary-700"
    contentClasses="relative"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="title">
        {{ __('shopper::pages/products.modals.variants.title') }}
    </x-slot>

    <x-slot name="content">
        <div class="overflow-y-scroll h-96">
            <div class="overflow-hidden">
                @if($inventories->isNotEmpty())
                    <div class="relative flex items-center justify-between p-4 border-b sm:px-5 border-secondary-200 dark:border-secondary-700">
                        <span class="relative z-0 inline-flex rounded-md shadow-sm">
                            <span class="relative inline-flex items-center px-2 py-2 bg-white border border-r-0 rounded-l-md border-secondary-300 dark:border-secondary-700 dark:bg-secondary-700">
                                <x-heroicon-o-location-marker class="w-5 h-5 text-secondary-500 dark:text-secondary-400" />
                            </span>
                            <x-shopper::forms.select wire:model.defer="inventory" id="inventory" class="block py-2 pl-3 -ml-px rounded-l-none pr-9 rounded-r-md" aria-label="{{ __('shopper::pages/products.modals.variants.select') }}">
                                <option value="0">{{ __('shopper::pages/products.modals.variants.select') }}</option>
                                @foreach($inventories as $inventory)
                                    <option value="{{ $inventory->id }}">{{ $inventory->name }}</option>
                                @endforeach
                            </x-shopper::forms.select>
                        </span>
                        <div class="relative z-0 inline-flex items-center leading-5 text-secondary-700 dark:text-secondary-300">
                            <span class="block mr-4 text-sm font-medium">{{ __('shopper::pages/products.quantity_available') }}</span>
                            <span class="mr-2 text-sm px-2 inline-flex leading-5 font-medium rounded-full {{ $product->stock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $product->stock }}
                            </span>
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-between px-4 py-6 mb-2 sm:px-6">
                    <div class="relative z-0 inline-flex items-center leading-5 text-secondary-700 dark:text-secondary-300">
                        <span class="block mr-4 text-sm font-medium">{{ __('shopper::pages/products.current_qty_inventory') }}</span>
                        <span class="mr-2 text-sm px-2 inline-flex leading-5 font-medium rounded-full {{ $currentStock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $currentStock }}
                        </span>
                    </div>
                    <div class="ml-4">
                        <div class="flex items-center">
                            <div class="flex items-center pr-4">
                                <p class="mr-4 text-sm font-medium text-secondary-700 dark:text-secondary-300">{{ $realStock }}</p>
                                <div>
                                    <div class="flex rounded-md shadow-sm">
                                        <div class="relative flex items-stretch grow focus-within:z-10">
                                            <x-shopper::forms.input wire:model="value" type="number" aria-label="{{ __('shopper::layout.forms.label.stock_number_value') }}" id="stockValue" step="1" min="0" class="block w-32 rounded-r-none rounded-l-md" placeholder="12" />
                                        </div>
                                        <button wire:click="decrementStock" type="button" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium bg-white border rounded-none border-secondary-300 dark:border-secondary-600 dark:bg-secondary-700 text-secondary-500 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-secondary-600 focus:z-10 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                                            <span>&minus;</span>
                                        </button>
                                        <button wire:click="incrementStock" type="button" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium bg-white border rounded-r-md border-secondary-300 dark:border-secondary-600 dark:bg-secondary-700 text-secondary-500 dark:text-secondary-400 hover:bg-secondary-50 dark:hover:bg-secondary-600 focus:z-10 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                                            <span>&plus;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <x-shopper::buttons.primary wire:click="updateCurrentStock" wire.loading.attr="disabled" type="button">
                                    <x-shopper::loader wire:loading wire:target="updateCurrentStock" class="text-white" />
                                    {{ __('shopper::layout.forms.actions.update') }}
                                </x-shopper::buttons.primary>
                                @if($histories->isNotEmpty())
                                    <div class="flex items-center pl-4 space-x-4">
                                        <x-shopper::buttons.default wire:click="export" type="button">
                                            <svg class="w-5 h-5 pr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                            </svg>
                                            {{ __('shopper::layout.forms.actions.export') }}
                                        </x-shopper::buttons.default>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @error('value')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ __('shopper::messages.validation.integer') }}</p>
                        @enderror
                    </div>
                </div>

                @if($histories->total() === 0)
                    <div class="flex flex-col items-center justify-center p-4 sm:p-6">
                        <span class="shrink-0">
                            <x-heroicon-o-document-text class="w-12 h-12 text-secondary-400" />
                        </span>
                        <h3 class="py-5 text-xl font-medium text-secondary-400">{{ __('No adjustments made to inventory.') }}</h3>
                    </div>
                @else
                    <div class="flex flex-col">
                        <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                            <div class="inline-block min-w-full overflow-hidden align-middle">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="border-b bg-secondary-50 border-secondary-200 dark:bg-secondary-700 dark:border-secondary-700">
                                            <x-shopper::tables.table-head>{{ __('shopper::messages.date') }}</x-shopper::tables.table-head>
                                            <x-shopper::tables.table-head>{{ __('shopper::messages.event') }}</x-shopper::tables.table-head>
                                            <x-shopper::tables.table-head>{{ __('shopper::messages.location') }}</x-shopper::tables.table-head>
                                            <x-shopper::tables.table-head class="text-right">{{ __('shopper::messages.adjustment') }}</x-shopper::tables.table-head>
                                            <x-shopper::tables.table-head class="text-right">{{ __('shopper::pages/products.inventory.movement') }}</x-shopper::tables.table-head>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-secondary-100 dark:bg-secondary-800 dark:divide-secondary-700">
                                        @foreach($histories as $inventoryHistory)
                                            <tr>
                                                <td class="px-6 py-4 text-sm leading-5 whitespace-no-wrap text-secondary-500 dark:text-secondary-400">
                                                    {{ $inventoryHistory->created_at->diffForHumans() }}
                                                </td>
                                                <td class="px-6 py-4 text-sm leading-5 whitespace-no-wrap text-secondary-500 dark:text-secondary-400">
                                                    {{ __($inventoryHistory->event) }}
                                                </td>
                                                <td class="px-6 py-4 text-sm leading-5 whitespace-no-wrap text-secondary-500 dark:text-secondary-400">
                                                    {{ $inventoryHistory->inventory->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-right {{ $inventoryHistory->old_quantity > 0 ? 'text-green-500': 'text-red-500' }}">
                                                    {{ $inventoryHistory->adjustment }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-right {{ $inventoryHistory->quantity > 0 ? 'text-secondary-500 dark:text-secondary-400': 'text-red-500' }}">
                                                    {{ $inventoryHistory->quantity }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="flex items-center justify-between px-4 py-3 border-t border-secondary-200 sm:px-6 dark:border-secondary-700">
                            <div class="flex justify-between flex-1 sm:hidden">
                                {{ $histories->links('shopper::livewire.wire-mobile-pagination-links') }}
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm leading-5 text-secondary-700 dark:text-secondary-300">
                                        {{ __('shopper::messages.showing') }}
                                        <span class="font-medium">{{ ($histories->currentPage() - 1) * $histories->perPage() + 1 }}</span>
                                        {{ __('shopper::messages.to') }}
                                        <span class="font-medium">{{ ($histories->currentPage() - 1) * $histories->perPage() + count($histories->items()) }}</span>
                                        {{ __('shopper::messages.of') }}
                                        <span class="font-medium"> {!! $histories->total() !!}</span>
                                        {{ __('shopper::messages.results') }}
                                    </p>
                                </div>
                                {{ $histories->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:w-auto">
            <x-shopper::buttons.default wire:click="$emit('closeModal')" type="button">
                {{ __('shopper::layout.forms.actions.close') }}
            </x-shopper::buttons.default>
        </span>
    </x-slot>

</x-shopper::modal>
