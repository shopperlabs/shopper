<x-shopper::container class="space-y-8 py-5">
    <x-shopper::heading :title="__('shopper::pages/settings/global.menu')" />

    <x-shopper::card>
        <div class="grid gap-4 sm:grid-cols-3">
            @foreach (resolve(\Shopper\Settings\SettingManager::class)->all() as $setting)
                @if ($setting->permission())
                    @can($setting->permission())
                        <x-shopper::menu.setting :$setting />
                    @endcan
                @else
                    <x-shopper::menu.setting :$setting />
                @endif
            @endforeach
        </div>
    </x-shopper::card>
</x-shopper::container>
