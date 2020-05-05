<div>

    <div class="my-6 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0 flex flex-row items-center justify-between md:flex-col md:items-start">
            <h2 class="text-2xl font-bold leading-7 text-primary-text sm:text-3xl sm:leading-9 sm:truncate">{{ __('Inventory') }}</h2>
            <div class="md:mt-2 ml-4 md:ml-0">
                <a href="#" class="text-gray-400 text-sm inline-flex items-center hover:text-gray-500 focus:text-gray-600 leading-5 transition duration-150 ease-in-out">
                    <svg fill="currentColor" viewBox="0 0 20 20" class="w-5 h-5 mr-2">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    <span>{{ __('Export') }}</span>
                </a>
            </div>
        </div>
        <div class="mt-4 flex md:mt-0 md:ml-4">
            <a href="{{ route('shopper.products.index') }}" class="btn btn-primary">{{ __('View products') }}</a>
        </div>
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="bg-white border-b border-gray-200">
            <div class="sm:hidden p-4">
                <select aria-label="Selected tab" class="form-select form-select-shopper block w-full pl-3 pr-10 py-2 text-base leading-6 sm:text-sm sm:leading-5 transition ease-in-out duration-150">
                    <option>{{ __('All') }}</option>
                </select>
            </div>
            <div class="hidden sm:block">
                <div class="">
                    <nav class="-mb-px flex">
                        <a href="#" class="whitespace-no-wrap ml-8 py-4 px-3 border-b-2 border-brand-500 font-medium text-sm leading-5 text-brand-400 focus:outline-none focus:text-brand-500 focus:border-brand-500">
                            {{ __('All') }}
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="px-4 py-4 sm:px-6">
            <label for="filter" class="sr-only">{{ __('Search products') }}</label>
            <div class="flex rounded-md shadow-sm">
                <div class="relative flex-grow focus-within:z-10">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                        </svg>
                    </div>
                    <input id="filter" wire:model.debounce.300ms="search" class="form-input block w-full rounded-none rounded-l-md pl-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5" placeholder="{{ __('Search products') }}" />
                    <span wire:loading class="spinner right-0 top-0 mt-5 mr-6"></span>
                </div>
                <button wire:click="sort('{{ $direction }}')" class="-ml-px relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-r-md text-gray-700 bg-gray-50 hover:text-gray-500 hover:bg-white focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"/>
                    </svg>
                    <span class="ml-2">{{ __('Sort') }}</span>
                </button>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full overflow-hidden">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="pl-6 py-3 border-b border-gray-200 text-left text-sm leading-4 text-gray-700 tracking-wider">
                                    <input id="select-all" type="checkbox" class="form-checkbox h-4 w-4 border-gray-300 text-brand-600 focus:shadow-outline-brand focus:border-brand-300 transition duration-150 ease-in-out" />
                                </th>
                                <th class="pl-3 pr-6 py-3 border-b border-gray-200 text-left text-sm font-medium leading-4 text-gray-700 tracking-wider">
                                    {{ __('Name') }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-sm font-medium leading-4 text-gray-700 tracking-wider">
                                    {{ __('Type') }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-sm font-medium leading-4 text-gray-700 tracking-wider">
                                    {{ __("Vendor") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-sm font-medium leading-4 text-gray-700 tracking-wider">
                                    {{ __("Quantity") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 text-left text-sm font-medium leading-4 text-gray-700 tracking-wider">
                                    {{ __("Last action") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($inventoryHistories as $history)
                                <tr class="hover:bg-gray-50">
                                    <td class="pl-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <input aria-label="select" type="checkbox" class="form-checkbox h-4 w-4 border-gray-300 text-brand-600 focus:shadow-outline-brand focus:border-brand-300 transition duration-150 ease-in-out" />
                                    </td>
                                    <td class="pl-3 pr-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <a href="{{ $history->route_name ? route($history->route_name, $history->stockable) : '#' }}" class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if($history->stockable->preview_image_link !== null)
                                                    <img class="h-10 w-10 rounded-md object-cover" src="{{ $history->stockable->preview_image_link }}" alt="{{ $history->name }}" />
                                                @else
                                                    <span class="flex items-center justify-center h-10 w-10 bg-gray-100 text-gray-300 rounded-md">
                                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-6 h-6">
                                                            <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm leading-5 font-medium text-gray-900">{{ $history->name }}</div>
                                                <div class="text-sm leading-5 text-gray-500">{{ $history->sku }}</div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <span class="mr-2 text-xs px-1.5 inline-flex leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $history->stockable_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                        {{ $history->inventory->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="flex items-center">
                                            <span class="text-sm inline-flex leading-5 font-semibold {{ $history->quantity < 10 ? 'text-red-600' : 'text-green-600' }}">
                                                {{ $history->quantity }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                        {{ __($history->event) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
