<x-livewire-tables::table.cell>
    <div class="flex items-center space-x-3 lg:pl-2">
        <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $row->approved ? 'bg-green-600': 'bg-secondary-200' }}"></div>
        <a href="{{ route('shopper.reviews.show', $row) }}" class="flex items-center">
            @if($row->reviewrateable->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
                <img class="h-8 w-8 rounded object-cover object-center" src="{{ $row->reviewrateable->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')) }}" alt="">
            @else
                <div class="bg-secondary-200 dark:bg-secondary-700 flex items-center justify-center h-8 w-8 rounded">
                    <x-heroicon-o-photograph class="w-5 h-5 text-secondary-400" />
                </div>
            @endif
            <span class="ml-2 truncate flex flex-col">
                <span>{{ $row->reviewrateable->name }}</span>
                <span class="text-xs leading-4 text-secondary-500 dark:text-secondary-400">{{ $row->reviewrateable->formatted_price ?? __('N/A') }}</span>
            </span>
        </a>
    </div>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell class="table-cell whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400">
    <div class="flex items-center">
        <div class="flex-shrink-0 h-8 w-8">
            <img class="h-8 w-8 rounded-full" src="{{ $row->author->picture }}" alt="">
        </div>
        <div class="ml-4 truncate">
            <div class="text-sm leading-5 font-medium text-secondary-900 dark:white">{{ $row->author->full_name }}</div>
            <div class="text-xs leading-4 text-secondary-500 truncate dark:text-secondary-400">{{ $row->author->email }}</div>
        </div>
    </div>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell class="hidden md:table-cell whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400">
    <div>
        <div class="flex items-center justify-between">
            <div>
                <span class="flex items-center">
                    <svg fill="{{ $row->rating >= 1 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    <svg fill="{{ $row->rating >= 2 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    <svg fill="{{ $row->rating >= 3 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    <svg fill="{{ $row->rating >= 4 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    <svg fill="{{ $row->rating === 5 ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-3 h-3 text-yellow-300" viewBox="0 0 24 24">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                </span>
            </div>
        </div>
        <p class="mt-1 text-sm text-secondary-900 font-medium leading-5 dark:text-white">{{ $row->title }}</p>
        <p class="text-sm text-secondary-500 leading-5 dark:text-secondary-400">{{ str_limit($row->content, 40) }}</p>
    </div>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell class="hidden md:table-cell whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400">
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $row->approved ? 'bg-green-100 text-green-800': 'bg-orange-100 text-orange-800' }}">
        {{ $row->approved ? __('Published') : __('Pending') }}
    </span>
</x-livewire-tables::table.cell>
{{--
<div class="hidden sm:block">
    <div class="align-middle inline-block min-w-full">
        <table class="min-w-full">
            <thead>
            <tr class="border-t border-secondary-200 dark:border-secondary-700">
                <th class="px-6 py-3 border-b border-secondary-200 bg-secondary-50 text-left text-xs leading-4 font-medium text-secondary-500 uppercase tracking-wider dark:border-secondary-700 dark:bg-secondary-700">
                    <span class="lg:pl-2">{{ __('Product') }}</span>
                </th>
                <th class="px-6 py-3 border-b border-secondary-200 bg-secondary-50 text-left text-xs leading-4 font-medium text-secondary-500 uppercase tracking-wider dark:border-secondary-700 dark:bg-secondary-700">
                    {{ __('Reviewer') }}
                </th>
                <th class="px-6 py-3 border-b border-secondary-200 bg-secondary-50 text-left text-xs leading-4 font-medium text-secondary-500 uppercase tracking-wider dark:border-secondary-700 dark:bg-secondary-700">
                    {{ __('Review') }}
                </th>
                <th class="px-6 py-3 border-b border-secondary-200 bg-secondary-50 text-left text-xs leading-4 font-medium text-secondary-500 uppercase tracking-wider dark:border-secondary-700 dark:bg-secondary-700">
                    {{ __('Status') }}
                </th>
                <th class="pr-6 py-3 border-b border-secondary-200 bg-secondary-50 text-right text-xs leading-4 font-medium text-secondary-500 uppercase tracking-wider dark:border-secondary-700 dark:bg-secondary-700"></th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-secondary-100 dark:bg-secondary-800 dark:divide-secondary-700" x-max="1">
            @forelse($reviews as $review)
                <tr>
                    <td class="px-6 py-3 text-sm leading-5 font-medium text-secondary-900 dark:text-white">

                    </td>
                    <td class="px-6 py-3  font-medium">

                    </td>
                    <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400">

                    </td>
                    <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400">

                    </td>
                    <td class="pr-6">
                        <div x-data="{ open: false }" x-on:item-removed.window="open = false" @keydown.escape="open = false" @click.away="open = false" class="relative flex justify-end items-center">
                            <button id="review-{{ $review->id }}" aria-has-popup="true" :aria-expanded="open" type="button" @click="open = !open" class="w-8 h-8 inline-flex items-center justify-center text-secondary-400 rounded-full bg-transparent hover:text-secondary-500 focus:outline-none focus:text-secondary-500 focus:bg-secondary-100 transition ease-in-out duration-150 dark:text-secondary-500 dark:hover:text-secondary-400 dark:focus:bg-secondary-700">
                                <x-heroicon-s-dots-vertical class="w-5 h-5" />
                            </button>
                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="mx-3 origin-top-right absolute right-7 top-0 w-48 mt-1 rounded-md shadow-lg"
                                 style="display: none;"
                            >
                                <div class="relative z-10 rounded-md bg-white shadow-xs dark:bg-secondary-800" role="menu" aria-orientation="vertical" aria-labelledby="project-options-menu-0">
                                    <div class="py-1">
                                        <a href="" type="button" class="group w-full flex items-center px-4 py-2 text-sm leading-5 text-secondary-700 hover:bg-secondary-100 hover:text-secondary-900 focus:outline-none focus:bg-secondary-100 focus:text-secondary-900 dark:text-secondary-300 dark:hover:bg-secondary-700 dark:hover:text-white dark:focus:bg-secondary-700" role="menuitem">
                                            <svg class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500 group-focus:text-secondary-500 dark:text-secondary-500 dark:group-focus:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            {{ __('View') }}
                                        </a>
                                    </div>
                                    <div class="border-t border-secondary-100 dark:border-secondary-700"></div>
                                    <div class="py-1">
                                        <button wire:click="remove({{ $review->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-secondary-700 hover:bg-secondary-100 hover:text-secondary-900 focus:outline-none focus:bg-secondary-100 focus:text-secondary-900 dark:text-secondary-300 dark:hover:bg-secondary-700 dark:hover-text-white" role="menuitem">
                                            <svg class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500 group-focus:text-secondary-500 dark:text-secondary-500 dark:group-focus:text-secondary-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ __('Delete') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-secondary-900 dark:text-white">
                        <div class="flex justify-center items-center space-x-2">
                            <svg class="h-8 w-8 text-secondary-400 dark:text-secondary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                            <span class="font-medium py-8 text-secondary-400 text-xl dark:text-secondary-500">{{ __('No review found') }}...</span>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>--}}
