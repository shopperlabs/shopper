<div class="flex items-center gap-2">
    <img
        class="size-8 rounded-full"
        src="{{ $order->customer->picture }}"
        alt="Avatar {{ $order->customer->full_name }}"
    />
    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $order->customer->full_name }}</p>
</div>
