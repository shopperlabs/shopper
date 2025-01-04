<x-shopper::form-slider-over action="store" :title="__('shopper::pages/settings/staff.add_admin')">
    {{ $this->form }}

    <x-shopper::alert
        class="mt-6"
        icon="untitledui-alert-triangle"
        :title="__('shopper::words.attention_needed')"
        :message="__('shopper::words.attention_description', ['role' => config('shopper.core.users.admin_role')])"
    />
</x-shopper::form-slider-over>
