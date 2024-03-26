<div class="flex items-center">
    <img class="h-8 w-8 rounded-lg object-cover object-center" src="{{ $row->getFirstMediaUrl(config('shopper.core.storage.collection_name'))}}" alt="" />
    <a href="{{ route('shopper.collections.edit', $row) }}" class="ml-2 truncate hover:text-gray-700 dark:hover:text-gray-300 font-medium">
        <span>{{ $row->name }}</span>
    </a>
</div>
