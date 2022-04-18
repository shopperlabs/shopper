<x-livewire-tables::table.cell>
    <div class="flex items-center">
        @if($row->getFirstMediaUrl(config('shopper.system.storage.disks.uploads')))
            <img class="h-8 w-8 rounded object-cover object-center" src="{{ $row->getFirstMediaUrl(config('shopper.system.storage.disks.uploads'))}}" alt="" />
        @else
            <div class="bg-secondary-200 dark:bg-secondary-700 flex items-center justify-center h-8 w-8 rounded">
                <x-heroicon-o-photograph class="w-5 h-5 text-secondary-400" />
            </div>
        @endif
        <a href="{{ route('shopper.collections.edit', $row) }}" class="ml-2 truncate hover:text-secondary-700 dark:hover:text-secondary-300 font-medium">
            <span>{{ $row->name }}</span>
        </a>
    </div>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 {{ $row->isAutomatic() ? 'bg-green-100 text-green-800' :  'bg-primary-100 text-primary-800' }}">
        <svg class="-ml-1 mr-1.5 h-2 w-2 {{ $row->isAutomatic()  ? 'text-green-400' :  'text-primary-400' }}" fill="currentColor" viewBox="0 0 8 8">
            <circle cx="4" cy="4" r="3" />
        </svg>
        {{ $row->isAutomatic() ? __('shopper::pages/collections.automatic') : __('shopper::pages/collections.manual') }}
    </span>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
    <span class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
        @if($row->rules->isNotEmpty())
            {{ ucfirst($row->firstRule()) }}
        @endif
    </span>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell>
    <time datetime="{{ $row->created_at->format('Y-m-d') }}" class="capitalize text-secondary-500 font-medium dark:text-secondary-400">{{ $row->created_at->formatLocalized('%d %B, %Y') }}</time>
</x-livewire-tables::table.cell>
