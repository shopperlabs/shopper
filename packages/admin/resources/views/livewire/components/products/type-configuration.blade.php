<div class="mt-5 flex items-start justify-between">
    <div class="max-w-xl">
        {{ $this->form }}
    </div>
    <div class="flex flex-1 items-center justify-end gap-2">
        <p
            x-data="{ shown: false, timeout: null }"
            x-init="@this.on('product-type.updated', () => { clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2500); })"
            x-show.transition.out.opacity.duration.1500ms="shown"
            x-transition:leave.opacity.duration.1500ms
            style="display: none"
            class="text-sm text-gray-500 dark:text-gray-400"
        >
            {{ __('shopper::notifications.saved') }}
        </p>
        <x-shopper::loader class="text-primary-500" wire:loading wire:target="hasConfig" aria-hidden="true" />
    </div>
</div>
