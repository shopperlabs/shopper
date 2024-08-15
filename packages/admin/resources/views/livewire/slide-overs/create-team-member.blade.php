<x-shopper::form-slider-over action="store" :title="__('shopper::pages/settings/staff.add_admin')">
    {{ $this->form }}
    <div class="mt-6 rounded-lg bg-warning-50 p-4 ring-1 ring-warning-200 dark:ring-warning-400/20 dark:bg-warning-400/10">
        <div class="flex">
            <div class="shrink-0">
                <x-untitledui-alert-triangle class="size-6 text-warning-400" aria-hidden="true" />
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium leading-5 text-warning-600">
                    {{ __('shopper::words.attention_needed') }}
                </h3>
                <p class="mt-2 text-sm leading-5 text-warning-500">
                    {{ __('shopper::words.attention_description', ['role' => config('shopper.core.users.admin_role')]) }}
                </p>
            </div>
        </div>
    </div>
</x-shopper::form-slider-over>
