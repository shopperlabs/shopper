<div class="flex items-start">
    <img
        class="h-8 w-8 rounded-full"
        src="{{ $row->customer->picture }}"
        alt="Avatar {{ $row->customer->full_name }}"
    />
    <div class="ml-2">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $row->customer->full_name }}</p>
        <p class="text-sm font-normal text-gray-400 dark:text-gray-500">{{ $row->customer->email }}</p>
    </div>
</div>
