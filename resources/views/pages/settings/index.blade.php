<x-shopper::layouts.app :title="__('Settings')">

    <div class="mt-4 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-secondary-900 dark:text-white sm:text-3xl sm:leading-9 sm:truncate">
                {{ __('Settings') }}
            </h2>
        </div>
    </div>

    <div class="mt-6 bg-white dark:bg-secondary-800 rounded-lg shadow-lg p-4 sm:p-5">
        <div class="z-20 relative grid gap-4 sm:gap-x-6 sm:gap-y-4 sm:grid-cols-3">
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
    </div>

</x-shopper::layouts.app>
