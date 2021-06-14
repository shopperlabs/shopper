<x-shopper-modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-gray-200 dark:border-gray-700"
    contentClasses="relative"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="title">
        {{ __('Stock management for this variant') }}
    </x-slot>

    <x-slot name="content">
        <div class="h-96 overflow-y-scroll">
            <div class="overflow-hidden">
                @if($inventories->count() > 1)
                    <div class="p-4 sm:px-5 relative flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
                        <span class="relative z-0 inline-flex shadow-sm rounded-md">
                            <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border-r-0 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-700">
                                <x-heroicon-o-location-marker class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                            </span>
                            <x-shopper-input.select wire:model="inventory" id="inventory" class="-ml-px block pl-3 pr-9 py-2 rounded-l-none rounded-r-md" aria-label="{{ __('Select inventory') }}">
                                @foreach($inventories as $inventory)
                                    <option value="{{ $inventory->id }}">{{ $inventory->name }}</option>
                                @endforeach
                            </x-shopper-input.select>
                        </span>
                        <div class="relative z-0 inline-flex items-center leading-5 text-gray-700 dark:text-gray-300">
                            <span class="block text-sm font-medium mr-4">{{ __('Quantity Available') }}</span>
                            <span class="mr-2 text-sm px-2 inline-flex leading-5 font-medium rounded-full {{ $product->stock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $product->stock }}
                            </span>
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-between px-4 sm:px-6 mb-2 py-6">
                    <div class="relative z-0 inline-flex items-center leading-5 text-gray-700 dark:text-gray-300">
                        <span class="block text-sm font-medium mr-4">{{ __('Current quantity on this inventory') }}</span>
                        <span class="mr-2 text-sm px-2 inline-flex leading-5 font-medium rounded-full {{ $currentStock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                            {{ $currentStock }}
                        </span>
                    </div>
                    <div class="ml-4">
                        <div class="flex items-center">
                            <div class="flex items-center pr-4">
                                <p class="mr-4 text-sm font-medium text-gray-700 dark:text-gray-300">{{ $realStock }}</p>
                                <div>
                                    <div class="flex rounded-md shadow-sm">
                                        <div class="relative flex items-stretch flex-grow focus-within:z-10">
                                            <x-shopper-input.text wire:model="value" type="number" aria-label="{{ __('Stock number value') }}" id="stockValue" step="1" min="0" class="block w-32 rounded-r-none rounded-l-md" placeholder="12" />
                                        </div>
                                        <button wire:click="decrementStock" type="button" class="-ml-px relative inline-flex items-center px-4 py-2 rounded-none border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                            <span>&minus;</span>
                                        </button>
                                        <button wire:click="incrementStock" type="button" class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-600 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                            <span>&plus;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <x-shopper-button wire:click="updateCurrentStock" wire.loading.attr="disabled" type="button">
                                    <x-shopper-loader wire:loading wire:target="updateCurrentStock" class="text-white" />
                                    {{ __('Update') }}
                                </x-shopper-button>
                                @if($histories->isNotEmpty())
                                    <div class="flex items-center pl-4 space-x-4">
                                        <x-shopper-default-button wire:click="export" type="button">
                                            <svg class="w-5 h-5 -ml-1 pr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                            </svg>
                                            {{ __('Export') }}
                                        </x-shopper-default-button>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @error('value')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ __('This number must be an integer.') }}</p>
                        @enderror
                    </div>
                </div>

                @if($histories->isEmpty())
                    <div class="flex flex-col items-center justify-center p-4 sm:p-6">
                        <span class="flex-shrink-0">
                            <x-heroicon-o-document-text class="h-12 w-12 text-gray-400" />
                        </span>
                        <h3 class="font-medium py-5 text-gray-400 text-xl">{{ __('No adjustments made to inventory.') }}</h3>
                    </div>
                @else
                    <div class="flex flex-col">
                        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                            <div class="align-middle inline-block min-w-full overflow-hidden">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-200 dark:bg-gray-700 dark:border-gray-700">
                                            <x-shopper-tables.table-head>{{ __('Date') }}</x-shopper-tables.table-head>
                                            <x-shopper-tables.table-head>{{ __('Event') }}</x-shopper-tables.table-head>
                                            <x-shopper-tables.table-head>{{ __('Inventory Place') }}</x-shopper-tables.table-head>
                                            <x-shopper-tables.table-head class="text-right">{{ __('Adjustment') }}</x-shopper-tables.table-head>
                                            <x-shopper-tables.table-head class="text-right">{{ __('Quantity Movement') }}</x-shopper-tables.table-head>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-100 dark:bg-gray-800 dark:divide-gray-700">
                                        @foreach($histories as $inventoryHistory)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500 dark:text-gray-400">
                                                    {{ $inventoryHistory->created_at->diffForHumans() }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500 dark:text-gray-400">
                                                    {{ __($inventoryHistory->event) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500 dark:text-gray-400">
                                                    {{ $inventoryHistory->inventory->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-right {{ $inventoryHistory->old_quantity > 0 ? 'text-green-500': 'text-red-500' }}">
                                                    {{ $inventoryHistory->adjustment }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-right {{ $inventoryHistory->quantity > 0 ? 'text-gray-500 dark:text-gray-400': 'text-red-500' }}">
                                                    {{ $inventoryHistory->quantity }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 dark:border-gray-700">
                            <div class="flex-1 flex justify-between sm:hidden">
                                {{ $histories->links('shopper::livewire.wire-mobile-pagination-links') }}
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm leading-5 text-gray-700 dark:text-gray-300">
                                        {{ __('Showing') }}
                                        <span class="font-medium">{{ ($histories->currentPage() - 1) * $histories->perPage() + 1 }}</span>
                                        {{ __('to') }}
                                        <span class="font-medium">{{ ($histories->currentPage() - 1) * $histories->perPage() + count($histories->items()) }}</span>
                                        {{ __('of') }}
                                        <span class="font-medium"> {!! $histories->total() !!}</span>
                                        {{ __('results') }}
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
            <x-shopper-default-button wire:click="$emit('closeModal')" type="button">
                {{ __('Close') }}
            </x-shopper-default-button>
        </span>
    </x-slot>

</x-shopper-modal>
