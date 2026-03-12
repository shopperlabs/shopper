<div class="flex h-full flex-col divide-y divide-gray-100 dark:divide-white/10">
    <div class="h-0 flex-1 overflow-y-auto py-6">
        <header class="px-4 sm:px-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-2">
                    <x-untitledui-switch-vertical class="size-5 text-gray-400" aria-hidden="true" />
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ __('shopper::words.reorder') }}
                    </h2>
                </div>
                <x-shopper::close-icon />
            </div>
        </header>

        <div class="mt-8 px-4" x-data="{ allCollapsed: false }">
            <div class="flex items-center justify-end gap-2">
                <button
                    type="button"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10"
                    x-on:click="allCollapsed = true; document.querySelectorAll('[data-children]').forEach(el => el.classList.add('hidden'))"
                >
                    <x-untitledui-rows class="size-3" aria-hidden="true" />
                    {{ __('shopper::words.collapse') }}
                </button>
                <button
                    type="button"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-white/10 dark:bg-white/5 dark:text-gray-300 dark:hover:bg-white/10"
                    x-on:click="allCollapsed = false; document.querySelectorAll('[data-children]').forEach(el => el.classList.remove('hidden'))"
                >
                    <x-untitledui-expand-06 class="size-3" aria-hidden="true" />
                    {{ __('shopper::words.expand') }}
                </button>
            </div>

            <ul
                x-data="nestedSortable({ parentId: null })"
                class="mt-4 space-y-0.5"
            >
                @foreach ($categories as $category)
                    <x-shopper::category-tree-item :$category />
                @endforeach
            </ul>
        </div>
    </div>

    <div class="flex shrink-0 justify-end space-x-4 p-4">
        <x-filament::button color="gray" wire:click="$dispatch('closePanel')" type="button">
            {{ __('shopper::forms.actions.close') }}
        </x-filament::button>
    </div>
</div>
