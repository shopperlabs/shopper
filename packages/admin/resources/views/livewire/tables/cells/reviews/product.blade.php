<div class="flex items-center space-x-3 lg:pl-2 truncate">
    <div class="shrink-0 w-2.5 h-2.5 rounded-full {{ $row->approved ? 'bg-green-600': 'bg-gray-200' }}"></div>
    <a href="{{ route('shopper.reviews.show', $row) }}" class="flex items-center group">
        @if($row->reviewrateable->getFirstMediaUrl(config('shopper.core.storage.collection_name')))
            <img class="h-8 w-8 rounded object-cover object-center" src="{{ $row->reviewrateable->getFirstMediaUrl(config('shopper.core.storage.collection_name')) }}" alt="">
        @else
            <div class="bg-gray-200 dark:bg-gray-700 flex items-center justify-center h-8 w-8 rounded">
                <x-heroicon-o-photograph class="w-5 h-5 text-gray-400" />
            </div>
        @endif
        <span class="ml-2 flex flex-col">
            <span class="group-hover:text-gray-700 dark:group-hover:text-gray-300">{{ $row->reviewrateable->name }}</span>
            <span class="text-sm text-gray-500 dark:text-gray-400">
                {{ $row->reviewrateable->getPriceAmount()?->formatted ?? __('N/A') }}
            </span>
        </span>
    </a>
</div>
