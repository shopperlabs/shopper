<x-shopper::container>
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:leading-9 sm:truncate">
            {{ __('shopper::words.settings') }}
        </h2>
    </div>

    <x-shopper::card class="mt-8 p-4">
        <div class="grid gap-4 sm:gap-x-6 sm:gap-y-4 sm:grid-cols-3">
            @foreach(config('shopper.settings.items') as $menu)
                @if($menu['permission'])
                    @can($menu['permission'])
                        <x-shopper::menu.setting :menu="$menu"/>
                    @endcan
                @else
                    <x-shopper::menu.setting :menu="$menu"/>
                @endif
            @endforeach
        </div>
    </x-shopper::card>
</x-shopper::container>
