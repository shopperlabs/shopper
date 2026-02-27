<x-shopper::container class="space-y-8 py-5">
    <x-shopper::heading :title="__('shopper::pages/settings/global.menu')" />

    {{ shopper()->getRenderHook(\Shopper\View\LayoutRenderHook::SETTINGS_INDEX_START) }}

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

    {{ shopper()->getRenderHook(\Shopper\View\LayoutRenderHook::SETTINGS_INDEX_END) }}
</x-shopper::container>
