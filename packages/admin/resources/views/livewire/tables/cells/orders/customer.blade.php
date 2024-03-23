<div class="flex items-start">
    <img class="h-8 w-8 rounded-full" src="{{ $row->customer->picture }}" alt="Avatar {{ $row->customer->full_name }}">
    <div class="ml-2">
        <p class="text-sm font-medium text-secondary-700 dark:text-secondary-300">{{ $row->customer->full_name }}</p>
        <p class="text-sm text-secondary-400 dark:text-secondary-500 font-normal">{{ $row->customer->email }}</p>
    </div>
</div>
