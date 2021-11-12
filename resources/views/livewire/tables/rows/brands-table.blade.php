<x-livewire-tables::table.cell>
    <div class="flex items-center space-x-3 lg:pl-2">
        <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $row->is_enabled ? 'bg-green-600': 'bg-secondary-400' }}"></div>
        <div class="flex items-center">
            @if($row->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
                <img class="h-8 w-8 rounded object-cover object-center" src="{{ $row->getFirstMediaUrl(config('shopper.system.storage.disks.uploads'))}}" alt="" />
            @else
                <div class="bg-secondary-200 dark:bg-secondary-700 flex items-center justify-center h-8 w-8 rounded">
                    <x-heroicon-o-photograph class="w-5 h-5 text-secondary-400" />
                </div>
            @endif
            <a href="{{ route('shopper.brands.edit', $row) }}" class="ml-2 truncate hover:text-secondary-600 dark:hover:text-secondary-400 font-medium">
                <span>{{ $row->name }} </span>
            </a>
        </div>
    </div>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
    @if($row->website)
        <a href="https://{{ $row->website }}" target="_blank" class="inline-flex items-center text-secondary-500 dark:text-secondary-400 font-medium text-sm leading-5">
            {{ $row->website }}
            <x-heroicon-o-external-link class="w-5 h-5 -mr-1 ml-2" />
        </a>
    @else
        <span class="inline-flex text-secondary-700 dark:text-secondary-500">&mdash;</span>
    @endif
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
    {{ $row->slug }}
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
    <time datetime="{{ $row->updated_at->format('Y-m-d') }}" class="capitalize text-secondary-500 dark:text-secondary-400">{{ $row->updated_at->formatLocalized('%d %B, %Y') }}</time>
</x-livewire-tables::table.cell>
