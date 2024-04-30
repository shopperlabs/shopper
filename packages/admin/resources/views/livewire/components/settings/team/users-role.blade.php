<x-shopper::container>
    <div class="flex flex-wrap items-baseline">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
            {{ __('shopper::words.users') }}
        </h3>
        <p class="ml-2 mt-1 truncate text-sm leading-5 text-gray-500 dark:text-gray-400">
            {{ __('shopper::pages/settings/staff.with_role_name', ['name' => $role->display_name]) }}
        </p>
    </div>
    <div class="mt-4">
        {{ $this->table }}
    </div>
</x-shopper::container>
