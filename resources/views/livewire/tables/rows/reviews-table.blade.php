<x-livewire-tables::table.cell>
    <div class="flex items-center space-x-3 lg:pl-2">
        <div class="shrink-0 w-2.5 h-2.5 rounded-full {{ $row->approved ? 'bg-green-600': 'bg-secondary-200' }}"></div>
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
        <div class="shrink-0 h-8 w-8">
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
        {{ $row->approved ? __('shopper::pages/products.reviews.published') : __('shopper::pages/products.reviews.pending') }}
    </span>
</x-livewire-tables::table.cell>
