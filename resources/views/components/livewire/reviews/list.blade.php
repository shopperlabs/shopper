<div
    x-data="{
        modal: false,
        options: ['all', 'approved', 'pending'],
        words: {'all': '{{ __("All") }}', 'approved': '{{ __("Approved") }}', 'pending': '{{ __("Pending") }}'},
        currentTab: 'all'
    }"
>

    <div class="my-6 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-600 sm:text-3xl sm:leading-9 sm:truncate">{{ __('Products Reviews') }}</h2>
        </div>
    </div>
    <div>
        <div class="sm:hidden mt-4">
            <select x-model="currentTab" aria-label="Selected tab" class="mt-1 form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5 transition ease-in-out duration-150">
                <template x-for="option in options" :key="option">
                    <option
                        x-bind:value="option"
                        x-text="words[option]"
                        x-bind:selected="option === currentTab"
                    ></option>
                </template>
            </select>
        </div>
        <div class="hidden sm:block">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex">
                    <button type="button" @click="currentTab = 'all' " class="whitespace-no-wrap py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'text-brand-400 border-brand-500 focus:text-brand-500 focus:border-brand-900' : currentTab === 'all' }">
                        {{ __("All") }}
                    </button>
                    <button type="button" @click="currentTab = 'approved' " class="whitespace-no-wrap ml-8 py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'text-brand-400 border-brand-500 focus:text-brand-500 focus:border-brand-900' : currentTab === 'approved' }">
                        {{ __("Approved") }}
                    </button>
                    <button type="button" @click="currentTab = 'pending' " class="whitespace-no-wrap ml-8 py-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'text-brand-400 border-brand-500 focus:text-brand-500 focus:border-brand-900' : currentTab === 'pending' }">
                        {{ __("Pending") }}
                    </button>
                </nav>
            </div>
        </div>
    </div>
    <div class="flex flex-col mt-5">
        <div x-show="currentTab === 'all'">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden rounded-md overflow-x-hidden">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-white text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Product") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-white text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Reviewer") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-white text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Review") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-white text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Status") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-white"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($reviews as $review)
                                <tr class="odd:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="flex items-center">
                                            @if($review->reviewrateable->preview_image_link !== null)
                                                <div class="flex-shrink-0 h-8 w-8 rounded-md overflow-hidden">
                                                    <img class="object-cover object-center w-full h-full block" src="{{ $review->reviewrateable->preview_image_link }}" alt="{{ $review->reviewrateable->id }}" />
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center h-8 w-8 bg-gray-100 text-gray-300 rounded-md">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="ml-4 truncate">
                                                <div class="text-sm leading-5 font-medium text-gray-900 truncate">{{ $review->reviewrateable->name }}</div>
                                                <div class="text-xs leading-4 text-gray-500">{{ $review->reviewrateable->sku ?? __("Sku no defined") }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-200">
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
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                        <div>
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="flex items-center">
                                                        <svg fill="{{ $review->rating >= 1 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                        <svg fill="{{ $review->rating >= 2 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                        <svg fill="{{ $review->rating >= 3 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                        <svg fill="{{ $review->rating >= 4 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                        <svg fill="{{ $review->rating === 5 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 leading-5">{{ $review->created_at->format('d/m/Y h:m') }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $review->approved ? 'bg-green-100 text-green-800': 'bg-orange-100 text-orange-800' }}">
                                            {{ $review->approved ? __("Published") : __("Pending") }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                                        <button wire:click="show({{ $review->id }})" type="button" class="text-brand-600 hover:text-brand-800">
                                            {{ __("Show") }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 whitespace-no-wrap border-b border-gray-200">
                                        <div class="flex flex-col text-center">
                                            <h1 class="text-sm font-medium text-gray-600 leading-6">{{ __("No reviews.") }}</h1>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div x-cloak x-show="currentTab === 'approved'">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden rounded-md overflow-x-hidden">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-white text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Product") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-white text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Reviewer") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-white text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Review") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-white"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($approved as $appReview)
                                <tr class="odd:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="flex items-center">
                                            @if($appReview->reviewrateable->preview_image_link !== null)
                                                <div class="flex-shrink-0 h-8 w-8 rounded-md overflow-hidden">
                                                    <img class="object-cover object-center w-full h-full block" src="{{ $appReview->reviewrateable->preview_image_link }}" alt="{{ $appReview->reviewrateable->id }}" />
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center h-8 w-8 bg-gray-100 text-gray-300 rounded-md">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="ml-4 truncate">
                                                <div class="text-sm leading-5 font-medium text-gray-900 truncate">{{ $appReview->reviewrateable->name }}</div>
                                                <div class="text-xs leading-4 text-gray-500">{{ $appReview->reviewrateable->sku ?? __("Sku no defined") }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <img class="h-8 w-8 rounded-full" src="{{ $appReview->author->picture }}" alt="">
                                            </div>
                                            <div class="ml-4 truncate">
                                                <div class="text-sm leading-5 font-medium text-gray-900">{{ $appReview->author->full_name }}</div>
                                                <div class="text-xs leading-4 text-gray-500 truncate">{{ $appReview->author->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                        <div>
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="flex items-center">
                                                        <svg fill="{{ $appReview->rating >= 1 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                        <svg fill="{{ $appReview->rating >= 2 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                        <svg fill="{{ $appReview->rating >= 3 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                        <svg fill="{{ $appReview->rating >= 4 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                        <svg fill="{{ $appReview->rating === 5 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 leading-5">{{ $appReview->created_at->format('d/m/Y h:m') }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                                        <button wire:click="show({{ $appReview->id }})" type="button" class="text-brand-600 hover:text-brand-800">
                                            {{ __("Show") }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 whitespace-no-wrap border-b border-gray-200">
                                        <div class="flex flex-col text-center">
                                            <h1 class="text-sm font-medium text-gray-600 leading-6">{{ __("No approved review.") }}</h1>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div x-cloak x-show="currentTab === 'pending'">
            <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div class="align-middle inline-block min-w-full shadow overflow-hidden rounded-md overflow-x-hidden">
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b border-gray-200 bg-white text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Product") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-white text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Reviewer") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-white text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __("Review") }}
                                </th>
                                <th class="px-6 py-3 border-b border-gray-200 bg-white"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($pending as $penReview)
                                <tr class="odd:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                        <div class="flex items-center">
                                            @if($penReview->reviewrateable->preview_image_link !== null)
                                                <div class="flex-shrink-0 h-8 w-8 rounded-md overflow-hidden">
                                                    <img class="object-cover object-center w-full h-full block" src="{{ $penReview->reviewrateable->preview_image_link }}" alt="{{ $penReview->reviewrateable->id }}" />
                                                </div>
                                            @else
                                                <div class="flex items-center justify-center h-8 w-8 bg-gray-100 text-gray-300 rounded-md">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="ml-4 truncate">
                                                <div class="text-sm leading-5 font-medium text-gray-900 truncate">{{ $penReview->reviewrateable->name }}</div>
                                                <div class="text-xs leading-4 text-gray-500">{{ $penReview->reviewrateable->sku ?? __("Sku no defined") }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <img class="h-8 w-8 rounded-full" src="{{ $penReview->author->picture }}" alt="">
                                            </div>
                                            <div class="ml-4 truncate">
                                                <div class="text-sm leading-5 font-medium text-gray-900">{{ $penReview->author->full_name }}</div>
                                                <div class="text-xs leading-4 text-gray-500 truncate">{{ $penReview->author->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                                        <div>
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <span class="flex items-center">
                                                        <svg fill="{{ $penReview->rating >= 1 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                        <svg fill="{{ $penReview->rating >= 2 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                        <svg fill="{{ $penReview->rating >= 3 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                        <svg fill="{{ $penReview->rating >= 4 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                        <svg fill="{{ $penReview->rating === 5 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 text-yellow-300" viewBox="0 0 24 24">
                                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500 leading-5">{{ $penReview->created_at->format('d/m/Y h:m') }}</p>
                                            <p class="mt-1 text-sm text-gray-600 leading-4">{{ str_limit($penReview->content, 20) }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                                        <button wire:click="show({{ $penReview->id }})" type="button" class="text-brand-600 hover:text-brand-800">
                                            {{ __("Show") }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-6 whitespace-no-wrap border-b border-gray-200">
                                        <div class="flex flex-col text-center">
                                            <h1 class="text-sm font-medium text-gray-600 leading-6">{{ __("No Pending review.") }}</h1>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
