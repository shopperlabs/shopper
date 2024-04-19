<x-shopper::container>
    <div class="min-w-0 flex-1">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:leading-9">
            {{ __('shopper::words.settings') }}
        </h2>
    </div>

    <x-shopper::card class="mt-8 p-4">
        <div class="grid gap-4 sm:grid-cols-3 sm:gap-x-6 sm:gap-y-4">
            @foreach (config('shopper.settings.items', []) as $menu)
                @if ($menu['permission'])
                    @can($menu['permission'])
                        <x-shopper::menu.setting :menu="$menu" />
                    @endcan
                @else
                    <x-shopper::menu.setting :menu="$menu" />
                @endif
            @endforeach
        </div>
    </x-shopper::card>
</x-shopper::container>
