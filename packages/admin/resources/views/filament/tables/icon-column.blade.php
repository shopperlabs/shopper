<div class="filament-icon-picker-icon-column px-4 py-3">
    @if ($icon = $getState())
        <x-icon class="size-6 text-gray-500 dark:text-gray-400" :name="$icon" aria-hidden="true" />
    @endif
</div>
