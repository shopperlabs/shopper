<div x-data="{ modal: false }">

    <div class="mt-4 pb-5 border-b border-gray-200 space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0">
        <h2 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">{{ __('Products reviews') }}</h2>
    </div>

    <div class="mt-6 bg-white shadow sm:rounded-md">
        <div class="p-4 sm:p-6 sm:pb-4">
            <div class="relative z-20 flex items-center space-x-4">
                <div class="flex flex-1">
                    <label for="filter" class="sr-only">{{ __('Search reviews') }}</label>
                    <div class="flex flex-grow rounded-md shadow-sm">
                        <div class="relative flex-grow focus-within:z-10">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                                </svg>
                            </div>
                            <input id="filter" wire:model.debounce.300ms="search" class="form-input block w-full rounded-none rounded-md pl-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5" placeholder="{{ __('Search review by product name') }}" />
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg wire:loading wire:target="search" class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-data="{ open: false }" @keydown.window.escape="open = false" @click.away="open = false" class="relative inline-block text-left">
                    <div>
                        <x-shopper-default-button @click="open = !open" type="button" id="options-menu" aria-haspopup="true" aria-expanded="true" x-bind:aria-expanded="open">
                            {{ __("Status") }}
                            <svg class="-mr-1 ml-2 h-5 w-5" x-description="Heroicon name: chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </x-shopper-default-button>
                    </div>
                    <div x-cloak x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg">
                        <div class="rounded-md bg-white shadow-xs">
                            <div class="py-1">
                                <div class="flex items-center py-2 px-4">
                                    <input wire:model="approved" id="approved" name="approved" type="radio" value="1" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                    <label for="approved" class="cursor-pointer ml-3">
                                        <span class="block text-sm leading-5 font-medium text-gray-700">{{ __("Approved") }}</span>
                                    </label>
                                </div>
                                <div class="flex items-center py-2 px-4">
                                    <input wire:model="approved" id="not_approved" name="approved" type="radio" value="0" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                    <label for="not_approved" class="cursor-pointer ml-3">
                                        <span class="block text-sm leading-5 font-medium text-gray-700">{{ __("Not Approved") }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="border-t border-gray-100"></div>
                            <div class="py-1">
                                <button wire:click="resetStatusFilter" type="button" class="block px-4 py-2 text-sm text-left leading-5 text-gray-500 hover:text-blue-600">{{ __("Clear") }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden sm:block">
            <div class="align-middle inline-block min-w-full">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-t border-gray-200">
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                <span class="lg:pl-2">{{ __("Product") }}</span>
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{ __("Reviewer") }}
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{ __("Review") }}
                            </th>
                            <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                {{ __("Status") }}
                            </th>
                            <th class="pr-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100" x-max="1">
                        @forelse($reviews as $review)
                            <tr>
                                <td class="px-6 py-3 max-w-0 w-full text-sm leading-5 font-medium text-gray-900">
                                    <div class="flex items-center space-x-3 lg:pl-2">
                                        <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $review->approved ? 'bg-green-600': 'bg-gray-400' }}"></div>
                                        <div class="flex items-center">
                                            @if($review->reviewrateable->files->count() > 0)
                                                <img class="h-8 w-8 rounded object-cover object-center" src="{{ $review->reviewrateable->files->first()->file_path }}" alt="">
                                            @else
                                                <div class="bg-gray-200 flex items-center justify-center h-8 w-8 rounded">
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <span class="ml-2 truncate flex flex-col">
                                                <span>{{ $review->reviewrateable->name }}</span>
                                                <span class="text-xs leading-4 text-gray-500">{{ $review->reviewrateable->formatted_price ?? __("N/A") }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 table-cell whitespace-no-wrap text-sm leading-5 text-gray-500 font-medium">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <img class="h-8 w-8 rounded-full" src="{{ $review->author->picture }}" alt="">
                                        </div>
                                        <div class="ml-4 truncate">
                                            <div class="text-sm leading-5 font-medium text-gray-900">{{ $review->author->full_name }}</div>
                                            <div class="text-xs leading-4 text-gray-500 truncate">{{ $review->author->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                    <div>
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="flex items-center">
                                                    <svg fill="{{ $review->rating >= 1 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                    </svg>
                                                    <svg fill="{{ $review->rating >= 2 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                    </svg>
                                                    <svg fill="{{ $review->rating >= 3 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                    </svg>
                                                    <svg fill="{{ $review->rating >= 4 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                    </svg>
                                                    <svg fill="{{ $review->rating === 5 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-800 font-medium leading-5">{{ $review->title }}</p>
                                        <p class="text-sm text-gray-500 leading-5">{{ str_limit($review->content, 40) }}</p>
                                    </div>
                                </td>
                                <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $review->approved ? 'bg-green-100 text-green-800': 'bg-orange-100 text-orange-800' }}">
                                        {{ $review->approved ? __("Published") : __("Pending") }}
                                    </span>
                                </td>
                                <td class="pr-6">
                                    <div x-data="{ open: false }" x-on:item-removed.window="open = false" @keydown.escape="open = false" @click.away="open = false" class="relative flex justify-end items-center">
                                        <button id="project-options-menu-0" aria-has-popup="true" :aria-expanded="open" type="button" @click="open = !open" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                            <svg class="w-5 h-5" x-description="Heroicon name: dots-vertical" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="mx-3 origin-top-right absolute right-7 top-0 w-48 mt-1 rounded-md shadow-lg" style="display: none;">
                                            <div class="relative z-10 rounded-md bg-white shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="project-options-menu-0">
                                                <div class="py-1">
                                                    <button wire:click="show({{ $review->id }})" type="button" class="group w-full flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                                                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                        {{ __("View") }}
                                                    </button>
                                                </div>
                                                <div class="border-t border-gray-100"></div>
                                                <div class="py-1">
                                                    <button wire:click="remove({{ $review->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                                                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" x-description="Heroicon name: trash" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        {{ __("Delete") }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                    <div class="flex justify-center items-center space-x-2">
                                        <svg class="h-8 w-8 text-cool-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        <span class="font-medium py-8 text-cool-gray-400 text-xl">{{ __("No review found") }}...</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white px-4 py-3 flex items-center rounded-b-md justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                {{ $reviews->links('shopper::livewire.wire-mobile-pagination-links') }}
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm leading-5 text-gray-700">
                        {{ __('Showing') }}
                        <span class="font-medium">{{ ($reviews->currentPage() - 1) * $reviews->perPage() + 1 }}</span>
                        {{ __('to') }}
                        <span class="font-medium">{{ ($reviews->currentPage() - 1) * $reviews->perPage() + count($reviews->items()) }}</span>
                        {{ __('of') }}
                        <span class="font-medium"> {!! $reviews->total() !!}</span>
                        {{ __('results') }}
                    </p>
                </div>
                {{ $reviews->links() }}
            </div>
        </div>
    </div>

    <x-shopper-learn-more name="reviews" link="#" />

    @if($reviewer !== null && $process)
        <div
            x-cloak
            x-on:open-review.window="modal = true"
            x-on:close-review.window="modal = false"
            x-show="modal"
            class="fixed bottom-0 z-100 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center"
        >
            <div
                x-cloak
                x-show="modal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-opacity"
            >
                <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
            </div>

            <div
                x-cloak
                x-show="modal"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="bg-white rounded-md overflow-hidden shadow-xl transform transition-all sm:max-w-2xl sm:w-full"
            >
                <div class="bg-white">
                    <div class="px-4 sm:px-6 py-4 max-h-md overflow-auto">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 inline-flex flex-shrink-0 items-start pt-3">
                                @if($reviewer->reviewrateable->preview_image_link !== null)
                                    <span class="flex-shrink-0 h-12 w-12 rounded-md overflow-hidden">
                                    <img class="object-cover object-center w-full h-full block" src="{{ $reviewer->reviewrateable->preview_image_link }}" alt="{{ $reviewer->reviewrateable->id }}" />
                                </span>
                                @else
                                    <span class="flex items-center justify-center h-10 w-10 bg-gray-100 text-gray-300 rounded-md">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </span>
                                @endif
                                <div class="ml-4 flex flex-col space-y-1">
                                    <p class="text-base leading-6 font-medium text-gray-900">{{ $reviewer->reviewrateable->name }}</p>
                                    @if($reviewer->reviewrateable->sku)
                                        <span class="text-gray-500 leading-5 text-sm">{{ $reviewer->reviewrateable->sku }}</span>
                                    @endif
                                    @if($reviewer->reviewrateable->price)
                                        <span class="font-medium text-brand-500 leading-5 text-sm">{{ shopper_money_format($reviewer->reviewrateable->price) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4">
                                <span class="inline-flex rounded-md shadow-sm">
                                    <button wire:click="remove({{ $reviewer->id }})" type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-500 focus:outline-none focus:border-red-700 focus:shadow-outline-red active:bg-red-700 transition ease-in-out duration-150">
                                        <svg class="-ml-0.5 h-4 w-4" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                            <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="py-2 mt-4">
                            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                <div class="px-4 py-5 sm:px-6">
                                    <dl class="grid grid-cols-1 col-gap-4 row-gap-8 sm:grid-cols-2">
                                        <div class="sm:col-span-1">
                                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                                {{ __("Reviewer") }}
                                            </dt>
                                            <dd class="mt-1 flex items-center text-sm leading-5 text-gray-900">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <img class="h-8 w-8 rounded-full" src="{{ $reviewer->author->picture }}" alt="">
                                                </div>
                                                <div class="ml-4 truncate">
                                                    <div class="text-sm leading-5 font-medium text-gray-900">{{ $reviewer->author->full_name }}</div>
                                                    <div class="text-xs leading-4 text-gray-500 truncate">{{ $reviewer->author->email }}</div>
                                                </div>
                                            </dd>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                                {{ __("Created At") }}
                                            </dt>
                                            <dd class="mt-1 text-sm leading-5 text-gray-900">
                                                {{ $reviewer->created_at->format('m-d-Y H:m') }}
                                            </dd>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                                {{ __("Rating") }}
                                            </dt>
                                            <dd class="mt-1 text-sm leading-5 text-gray-900">
                                                <span class="flex items-center">
                                                    <svg fill="{{ $reviewer->rating >= 1 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                    </svg>
                                                    <svg fill="{{ $reviewer->rating >= 2 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                    </svg>
                                                    <svg fill="{{ $reviewer->rating >= 3 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                    </svg>
                                                    <svg fill="{{ $reviewer->rating >= 4 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                    </svg>
                                                    <svg fill="{{ $reviewer->rating === 5 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                    </svg>
                                                </span>
                                            </dd>
                                        </div>
                                        <div class="sm:col-span-1">
                                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                                {{ __("Status") }}
                                            </dt>
                                            <dd class="flex items-center mt-1 text-sm leading-5 text-gray-900">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $reviewer->approved ? 'bg-green-100 text-green-800': 'bg-orange-100 text-orange-800' }}">
                                                    {{ $reviewer->approved ? __("Published") : __("Pending") }}
                                                </span>
                                                <div class="ml-4 flex items-center relative">
                                                    <button wire:click="toggleApproved({{ $reviewer->id }})" type="button" class="mr-3 text-sm font-medium text-gray-600 leading-5">
                                                        {{ __("Update status") }}
                                                    </button>
                                                    <span wire:loading wire:target="toggleApproved" class="spinner right-0 ml-3"></span>
                                                </div>
                                            </dd>
                                        </div>
                                        <div class="sm:col-span-2">
                                            <dt class="text-sm leading-5 font-medium text-gray-500">
                                                {{ __("Content") }}
                                            </dt>
                                            <dd class="mt-1 text-sm leading-5 text-gray-900">
                                                {{ $reviewer->content }}
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 relative z-30 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                        <button @click="modal = false;" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                            {{ __('Close') }}
                        </button>
                    </span>
                </div>
            </div>
        </div>
    @endif
</div>
