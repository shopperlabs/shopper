<div class="flex items-center">
    <img class="h-7 w-7 rounded-full" src="{{ $customer->picture }}" alt="Avatar {{ $customer->full_name }}">
    <div class="ml-3">
        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $customer->full_name }}</p>
        <p class="text-xs leading-4 text-gray-400 dark:text-gray-500">{{ $customer->email }}</p>
    </div>
</div>
