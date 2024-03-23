<div class="max-w-md whitespace-no-wrap text-sm leading-5 font-medium text-secondary-900 dark:text-white">
    <div class="flex items-center lg:pl-2">
        <img class="h-10 w-10 rounded-lg object-cover" src="{{ $row->picture }}" alt="{{ $row->full_name }}">
        <a href="{{ route('shopper.customers.show', $row) }}" class="ml-4">
            <div class="text-sm leading-5 font-medium text-secondary-900 dark:text-white">
                {{ $row->full_name }}
            </div>
            <div class="flex items-center">
                @if($row->email_verified_at)
                    <x-untitledui-check-verified-02 class="w-5 h-5 text-green-500" />
                @else
                    <x-untitledui-alert-circle class="w-5 h-5 text-danger-500" />
                @endif
                <span class="ml-1.5 text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ $row->email }}</span>
            </div>
        </a>
    </div>
</div>
