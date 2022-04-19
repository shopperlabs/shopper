<x-livewire-tables::table.cell>
    <div class="flex items-center space-x-3 lg:pl-2">
        <div class="shrink-0 w-2.5 h-2.5 rounded-full {{ $row->is_enabled ? 'bg-green-600': 'bg-secondary-400 dark:bg-secondary-600' }}"></div>
        <div class="flex items-center">
            @if($row->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
                <img class="h-8 w-8 rounded object-cover object-center" src="{{ $row->getFirstMediaUrl(config('shopper.system.storage.disks.uploads'))}}" alt="" />
            @else
                <div class="bg-secondary-200 dark:bg-secondary-700 flex items-center justify-center h-8 w-8 rounded">
                    <x-heroicon-o-photograph class="w-5 h-5 text-secondary-400" />
                </div>
            @endif
            <a href="{{ route('shopper.categories.edit', $row) }}" class="ml-2 truncate hover:text-secondary-600 font-medium">
                <span>
                    {{ $row->name }} @if($row->parent_id)<span class="text-secondary-500 font-normal dark:text-secondary-400">{{ __('shopper::pages/categories.parent', ['parent' => $row->parent_name]) }}</span>@endif
                </span>
            </a>
        </div>
    </div>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
    <span class="text-secondary-500 font-medium dark:text-secondary-400 truncate">
        {{ $row->slug }}
    </span>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
    <time datetime="{{ $row->created_at->format('Y-m-d') }}" class="capitalize text-secondary-500 font-medium dark:text-secondary-400">{{ $row->created_at->formatLocalized('%d %B, %Y') }}</time>
</x-livewire-tables::table.cell>
