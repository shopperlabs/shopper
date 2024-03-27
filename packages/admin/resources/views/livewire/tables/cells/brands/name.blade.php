<div class="flex items-center space-x-3 lg:pl-2">
    <div class="shrink-0 w-2.5 h-2.5 rounded-full {{ $row->is_enabled ? 'bg-green-600': 'bg-gray-400' }}"></div>
    <div class="flex items-center">
        <img class="h-8 w-8 rounded-lg object-cover object-center" src="{{ $row->getFirstMediaUrl(config('shopper.core.storage.collection_name'))}}" alt="" />
        <a href="{{ route('shopper.brands.edit', $row) }}" class="ml-2 truncate text-gray-900 hover:text-gray-600 dark:hover:text-gray-400 dark:text-white font-medium">
            {{ $row->name }}
        </a>
    </div>
</div>
