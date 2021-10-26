<x-livewire-tables::table.cell>
    <div class="flex items-center space-x-3 lg:pl-2">
        <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $row->is_enabled ? 'bg-green-600': 'bg-gray-400 dark:bg-gray-600' }}"></div>
        <div class="flex items-center">
            @if($row->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
                <img class="h-8 w-8 rounded object-cover object-center" src="{{ $row->getFirstMediaUrl(config('shopper.system.storage.disks.uploads'))}}" alt="" />
            @else
                <div class="bg-gray-200 dark:bg-gray-700 flex items-center justify-center h-8 w-8 rounded">
                    <x-heroicon-o-photograph class="w-5 h-5 text-gray-400" />
                </div>
            @endif
            <a href="{{ route('shopper.categories.edit', $row) }}" class="ml-2 truncate hover:text-gray-600">
                <span>
                    {{ $row->name }} @if($row->parent_id)<span class="text-gray-500 font-normal dark:text-gray-400">{{ __('in :parent', ['parent' => $row->parent_name]) }}</span>@endif
                </span>
            </a>
        </div>
    </div>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
    <span class="text-gray-500 font-medium dark:text-gray-400 truncate">
        {{ $row->slug }}
    </span>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
    <time datetime="{{ $row->created_at->format('Y-m-d') }}" class="capitalize text-gray-500 font-medium dark:text-gray-400">{{ $row->created_at->formatLocalized('%d %B, %Y') }}</time>
</x-livewire-tables::table.cell>
