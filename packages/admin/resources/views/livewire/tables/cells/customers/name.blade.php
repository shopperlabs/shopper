<div class="text-sm leading-5 font-medium text-gray-900 dark:text-white">
    <div class="flex items-center space-x-2">
        <img
            class="h-8 w-8 rounded-full object-cover"
            src="{{ $getRecord()->picture }}"
            alt="{{ $getRecord()->full_name }}"
        >
        <x-shopper::link href="{{ route('shopper.customers.show', $getRecord()) }}">
            <span class="text-sm leading-5 font-medium">
                {{ $getRecord()->full_name }}
            </span>
        </x-shopper::link>
    </div>
</div>
