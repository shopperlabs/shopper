<x-livewire-tables::table.cell class="max-w-md whitespace-no-wrap text-sm leading-5 font-medium text-gray-900 dark:text-white">
    <div class="flex items-center lg:pl-2">
        <div class="flex-shrink-0 h-10 w-10">
            <img class="h-10 w-10 rounded-lg" src="{{ $row->picture }}" alt="">
        </div>
        <a href="{{ route('shopper.customers.show', $row) }}" class="ml-4">
            <div class="text-sm leading-5 font-medium text-gray-900 dark:text-white">
                {{ $row->full_name }}
            </div>
            <div class="flex items-center">
                @if($row->email_verified_at)
                    <x-heroicon-s-shield-check class="w-5 h-5 text-green-500" />
                @else
                    <x-heroicon-s-shield-exclamation class="w-5 h-5 text-red-500" />
                @endif
                <span class="ml-1.5 text-sm leading-5 text-gray-500 dark:text-gray-400">{{ $row->email }}</span>
            </div>
        </a>
    </div>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell class="text-gray-500 font-medium">
    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $row->opt_in ? 'bg-green-100 text-green-800': 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
        {{ $row->opt_in ? __('Subscribed') : __('Not subscribed') }}
    </span>
</x-livewire-tables::table.cell>

<x-livewire-tables::table.cell class="hidden md:table-cell whitespace-no-wrap text-sm leading-5 text-gray-500 dark:text-gray-400">
    <time datetime="{{ $row->created_at->format('Y-m-d') }}" class="capitalize">{{ $row->created_at->formatLocalized('%d %B %Y') }}</time>
</x-livewire-tables::table.cell>
