<x-shopper::container class="py-5">
    <x-shopper::heading :title="__('shopper::pages/settings/global.menu')" />

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
