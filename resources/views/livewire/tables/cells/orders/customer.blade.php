<div class="flex items-center">
    <img class="h-7 w-7 rounded-full" src="{{ $customer->picture }}" alt="Avatar {{ $customer->full_name }}">
    <div class="ml-3">
        <p class="text-sm font-medium text-secondary-700 dark:text-secondary-300">{{ $customer->full_name }}</p>
        <p class="text-xs leading-4 text-secondary-400 dark:text-secondary-500">{{ $customer->email }}</p>
    </div>
</div>
