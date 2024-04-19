<div class="flex items-center space-x-3">
    <div class="{{ $row->is_visible ? 'bg-green-600' : 'bg-gray-400' }} h-2.5 w-2.5 shrink-0 rounded-full"></div>
    <div class="flex items-center truncate">
        <img
            class="h-8 w-8 rounded-lg object-cover object-center"
            src="{{ $row->getFirstMediaUrl(config('shopper.core.storage.collection_name')) }}"
            alt=""
        />

        @can('read_products')
            <a
                href="{{ route('shopper.products.edit', $row) }}"
                class="ml-2 truncate hover:text-gray-600 dark:hover:text-gray-400"
            >
                <span>{{ $row->name }}</span>
            </a>
        @else
            <span class="ml-2 truncate">{{ $row->name }}</span>
        @endcan
    </div>
</div>
