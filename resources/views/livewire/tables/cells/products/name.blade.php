<div class="flex items-center space-x-3">
    <div class="shrink-0 w-2.5 h-2.5 rounded-full {{ $product->is_visible ? 'bg-green-600': 'bg-secondary-400' }}"></div>
    <div class="flex items-center truncate">
        @if($product->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
            <img class="h-8 w-8 rounded object-cover object-center" src="{{ $product->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')) }}" alt="" />
        @else
            <div class="bg-secondary-200 dark:bg-secondary-700 flex items-center justify-center h-8 w-8 rounded">
                <x-heroicon-o-photograph class="w-5 h-5 text-secondary-400" />
            </div>
        @endif
        @can('read_products')
            <a href="{{ route('shopper.products.edit', $product) }}" class="ml-2 truncate hover:text-secondary-600 dark:hover:text-secondary-400">
                <span>{{ $product->name }} </span>
            </a>
        @else
            <span class="ml-2 truncate">{{ $product->name }} </span>
        @endcan
    </div>
</div>
