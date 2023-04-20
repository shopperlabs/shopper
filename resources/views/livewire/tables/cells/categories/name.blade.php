<div class="flex items-center space-x-3 lg:pl-2">
    <div class="shrink-0 w-2.5 h-2.5 rounded-full {{ $category->is_enabled ? 'bg-green-600': 'bg-secondary-400 dark:bg-secondary-600' }}"></div>
    <div class="flex items-center">
        @if($category->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
            <img class="h-8 w-8 rounded object-cover object-center" src="{{ $category->getFirstMediaUrl(config('shopper.system.storage.disks.uploads'))}}" alt="" />
        @else
            <div class="bg-secondary-200 dark:bg-secondary-700 flex items-center justify-center h-8 w-8 rounded">
                <x-heroicon-o-photograph class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
            </div>
        @endif
        <a href="{{ route('shopper.categories.edit', $category) }}" class="ml-2 truncate hover:text-secondary-600 font-medium">
            <span>
                {{ $category->name }}
                @if($category->parent_id)
                    <span class="text-secondary-500 font-normal dark:text-secondary-400">
                        {{ __('shopper::pages/categories.parent', ['parent' => $category->parent_name]) }}
                    </span>
                @endif
            </span>
        </a>
    </div>
</div>
