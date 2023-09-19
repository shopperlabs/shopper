<x-shopper::layouts.setting :title="__('shopper::pages/settings.roles_permissions.roles') . ' ~ ' . $role->display_name">

    <livewire:shopper-settings.management.role :role="$role" />

</x-shopper::layouts.setting>
