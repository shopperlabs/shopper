<x-shopper::container class="py-5 space-y-8">
    <x-shopper::heading :title="__('shopper::pages/settings/global.menu')" />

    <x-shopper::card>
        <div class="grid gap-4 sm:grid-cols-3">
            @foreach (config('shopper.settings.items', []) as $menu)
                @if (isset($menu['permission']))
                    @can($menu['permission'])
                        <x-shopper::menu.setting :$menu />
                    @endcan
                @else
                    <x-shopper::menu.setting :$menu />
                @endif
            @endforeach
        </div>
    </x-shopper::card>
</x-shopper::container>
