<div class="bg-white shadow-md rounded-md overflow-hidden">
    @if($inventories->count() > 1)
        <div class="px-4 sm:px-6 py-3 relative flex items-center justify-between border-b border-gray-200">
            <span class="relative z-0 inline-flex shadow-sm rounded-md">
                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border-r-0 border border-gray-300 bg-white">
                    <svg class="h-6 w-6" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </span>
                <select wire:model="inventory" id="inventory" class="-ml-px block form-select w-full pl-3 pr-9 py-2 rounded-l-none rounded-r-md border border-gray-300 bg-white text-sm leading-4 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150" aria-label="{{ __("Select inventory") }}">
                    @foreach($inventories as $inventory)
                        <option value="{{ $inventory->id }}">{{ $inventory->name }}</option>
                    @endforeach
                </select>
            </span>
            <div class="relative z-0 inline-flex items-center leading-5 text-gray-700">
                <span class="block text-sm font-medium mr-4">{{ __("Quantity Available") }}</span>
                    <span class="mr-2 text-sm px-2 inline-flex leading-5 font-semibold rounded-full {{ $product->stock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                    {{ $product->stock }}
                </span>
            </div>
        </div>
    @endif
    <div class="flex items-center justify-between px-4 sm:px-6 mb-2 py-6">
        <div class="relative z-0 inline-flex items-center leading-5 text-gray-700">
            <span class="block text-sm font-medium mr-4">{{ __("Current quantity on this inventory") }}</span>
            <span class="mr-2 text-sm px-2 inline-flex leading-5 font-semibold rounded-full {{ $currentStock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                {{ $currentStock }}
            </span>
        </div>
        <div class="ml-4 flex items-center">
            <div class="flex items-center">
                <p class="text-sm font-medium text-gray-600 mr-4">{{ $realStock }}</p>
                <div>
                    <div class="relative overflow-hidden">
                        <input wire:model="value" aria-label="{{ __("Number") }}" type="number" step="1" class="form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out pr-10">
                        <div class="absolute inset-y-0 right-0 flex flex-col items-center justify-center border-l-2 border-gray-200 divide-y divide-gray-200 overflow-hidden">
                            <button wire:click="incrementStock" type="button" class="text-sm leading-4 px-2.5 h-auto w-full focus:shadow-inner inline-flex items-center outline-none rounded-r-md transition ease-in-out duration-150">+</button>
                            <button wire:click="decrementStock" type="button" class="text-sm leading-4 px-2.5 h-auto w-full focus:shadow-inner inline-flex items-center outline-none rounded-r-md transition ease-in-out duration-150">-</button>
                        </div>
                    </div>
                    @error('value')
                        <p class="mt-2 text-sm text-red-600">{{ __("This number must be an integer.") }}</p>
                    @enderror
                </div>
            </div>
            <button wire:click="updateCurrentStock" type="button" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-600 focus:outline-none focus:border-brand-700 focus:shadow-outline-brand active:bg-brand-700 transition ease-in-out duration-150">
                <span wire:loading wire:target="updateCurrentStock" class="pr-2">
                    <span class="btn-spinner"></span>
                </span>
                {{ __("Update") }}
            </button>
        </div>
    </div>
    @if($histories->isEmpty())
        <div class="flex flex-col items-center justify-center px-4 sm:px-6 mb-2 py-6 mt-4">
                <span class="flex-shrink-0 h-20 w-20">
                    <svg class="w-full h-full text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
            <h3 class="text-gray-600 font-medium text-lg leading-5 mt-2">{{ __("No adjustments made to inventory.") }}</h3>
        </div>
    @else
        <div class="flex flex-col">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full overflow-hidden">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-sm leading-4 font-medium text-gray-700 tracking-wider">
                                    {{ __("Date") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-sm leading-4 font-medium text-gray-700 tracking-wider">
                                    {{ __("Event") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-sm leading-4 font-medium text-gray-700 tracking-wider">
                                    {{ __("Inventory Place") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-sm leading-4 font-medium text-gray-700 tracking-wider">
                                    {{ __("Adjustment") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-sm leading-4 font-medium text-gray-700 tracking-wider">
                                    {{ __("Quantity Movement") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($histories as $inventoryHistory)
                                <tr>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $inventoryHistory->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ __($inventoryHistory->event) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                        {{ $inventoryHistory->inventory->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-right {{ $inventoryHistory->old_quantity > 0 ? 'text-green-500': 'text-red-500' }}">
                                        {{ $inventoryHistory->adjustment }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-right {{ $inventoryHistory->quantity > 0 ? 'text-gray-500': 'text-red-500' }}">
                                        {{ $inventoryHistory->quantity }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    {{ $histories->links('shopper::components.livewire.wire-mobile-pagination-links') }}
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm leading-5 text-gray-700">
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
