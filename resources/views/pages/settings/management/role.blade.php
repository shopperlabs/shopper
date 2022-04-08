<x-shopper::layouts.setting :title="__('Roles') . ' ' . $role->display_name">

    <livewire:shopper-settings.management.role :role="$role" />

</x-shopper::layouts.setting>
