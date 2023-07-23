<div class="flex items-center">
    <img class="h-6 w-6 rounded-full" src="{{ $row->customer->picture }}" alt="Avatar {{ $row->customer->full_name }}">
    <div class="ml-3">
        <p class="text-sm font-medium text-secondary-700 dark:text-secondary-300">{{ $row->customer->full_name }}</p>
        <p class="text-xs leading-4 text-secondary-400 dark:text-secondary-500">{{ $row->customer->email }}</p>
    </div>
</div>
