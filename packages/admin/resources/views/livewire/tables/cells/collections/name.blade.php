<div class="flex items-center">
    @if($row->getFirstMediaUrl(config('shopper.core.storage.collection_name')))
        <img class="h-8 w-8 rounded object-cover object-center" src="{{ $row->getFirstMediaUrl(config('shopper.core.storage.collection_name'))}}" alt="" />
    @else
        <div class="bg-secondary-200 dark:bg-secondary-700 flex items-center justify-center h-8 w-8 rounded">
            <x-heroicon-o-photograph class="w-5 h-5 text-secondary-400" />
        </div>
    @endif
    <a href="{{ route('shopper.collections.edit', $row) }}" class="ml-2 truncate hover:text-secondary-700 dark:hover:text-secondary-300 font-medium">
        <span>{{ $row->name }}</span>
    </a>
</div>
