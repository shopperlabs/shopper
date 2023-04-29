<div class="max-w-md whitespace-no-wrap text-sm leading-5 font-medium text-secondary-900 dark:text-white">
    <div class="flex items-center lg:pl-2">
        <div class="shrink-0 h-10 w-10">
            <img class="h-10 w-10 rounded-lg" src="{{ $row->picture }}" alt="">
        </div>
        <a href="{{ route('shopper.customers.show', $row) }}" class="ml-4">
            <div class="text-sm leading-5 font-medium text-secondary-900 dark:text-white">
                {{ $row->full_name }}
            </div>
            <div class="flex items-center">
                @if($row->email_verified_at)
                    <x-heroicon-s-shield-check class="w-5 h-5 text-green-500" />
                @else
                    <x-heroicon-s-shield-exclamation class="w-5 h-5 text-red-500" />
                @endif
                <span class="ml-1.5 text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ $row->email }}</span>
            </div>
        </a>
    </div>
</div>
