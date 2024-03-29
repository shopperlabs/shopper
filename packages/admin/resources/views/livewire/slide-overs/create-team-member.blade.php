<x-shopper::form-slider-over
    action="store"
    :title="__('shopper::pages/settings.roles_permissions.add_admin')"
>
    {{ $this->form }}
    <div class="mt-6 rounded-lg bg-yellow-50 p-4">
        <div class="flex">
            <div class="shrink-0">
                <x-untitledui-alert-triangle class="h-6 w-6 text-yellow-400" aria-hidden="true" />
            </div>
            <div class="ml-3">
                <h3 class="text-sm leading-5 font-medium text-yellow-800">
                    {{ __('shopper::words.attention_needed') }}
                </h3>
                <p class="mt-2 text-sm leading-5 text-yellow-700">
                    {{ __('shopper::words.attention_description') }}
                </p>
            </div>
        </div>
    </div>
</x-shopper::form-slider-over>
