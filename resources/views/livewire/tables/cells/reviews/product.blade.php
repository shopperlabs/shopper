<div class="flex items-center space-x-3 lg:pl-2 truncate">
    <div class="shrink-0 w-2.5 h-2.5 rounded-full {{ $row->approved ? 'bg-green-600': 'bg-secondary-200' }}"></div>
    <a href="{{ route('shopper.reviews.show', $row) }}" class="flex items-center group">
        @if($row->reviewrateable->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
            <img class="h-8 w-8 rounded object-cover object-center" src="{{ $row->reviewrateable->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')) }}" alt="">
        @else
            <div class="bg-secondary-200 dark:bg-secondary-700 flex items-center justify-center h-8 w-8 rounded">
                <x-heroicon-o-photograph class="w-5 h-5 text-secondary-400" />
            </div>
        @endif
        <span class="ml-2 flex flex-col">
            <span class="group-hover:text-secondary-700 dark:group-hover:text-secondary-300">{{ $row->reviewrateable->name }}</span>
            <span class="text-xs leading-4 text-secondary-500 dark:text-secondary-400">
                {{ $row->reviewrateable->formatted_price ?? __('N/A') }}
            </span>
        </span>
    </a>
</div>
