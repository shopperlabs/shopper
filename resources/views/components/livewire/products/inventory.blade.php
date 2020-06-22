<div class="bg-white shadow-md rounded-md overflow-hidden">
    <div class="flex items-center justify-between px-4 sm:px-6 mb-2 py-6">
        <div class="relative z-0 inline-flex items-center leading-5 text-gray-700">
            <span class="block text-sm font-medium mr-4">{{ __("Quantity available") }}</span>
            <span class="mr-2 text-sm px-2 inline-flex leading-5 font-semibold rounded-full {{ $realStock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                {{ $product->stock }}
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
                        <p class="mt-2 text-sm text-red-600" id="email-error">{{ __("This number must be an integer.") }}</p>
                    @enderror
                </div>
            </div>
            <button wire:click="updateCurrentStock" type="button" class="ml-3 btn btn-primary">
                <span wire:loading wire:target="updateCurrentStock" class="pr-2">
                    <span class="btn-spinner"></span>
                </span>
                {{ __("Update") }}
            </button>
        </div>
    </div>
    @if($product->inventoryHistories->isEmpty())
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
                            @foreach($product->inventoryHistories as $inventoryHistory)
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
        </div>
    @endif
</div>
