<div class="flex h-full flex-col divide-y divide-gray-200 dark:divide-white/10">
    <div class="h-0 flex-1 overflow-y-auto py-6">
        <header class="px-4 sm:px-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center">
                    <x-untitledui-switch-vertical class="mr-2 size-5 text-gray-400" aria-hidden="true" />
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ __('shopper::words.reorder') }}
                    </h2>
                </div>
                <div class="ml-3 flex h-7 items-center gap-2">
                    <x-shopper::escape />
                    <button
                        type="button"
                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-900 dark:text-gray-500 dark:ring-offset-gray-900 dark:hover:text-gray-300"
                        wire:click="$dispatch('closePanel')"
                    >
                        <span class="sr-only">Close panel</span>
                        <x-untitledui-x class="size-6" stroke-width="1.5" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </header>

        <div
            wire:sortable="updateGroupOrder"
            wire:sortable-group="updateCategoryOrder"
            class="mt-8 flex-1 px-4 sm:px-6"
        >
            @foreach ($categories as $category)
                <div
                    wire:sortable.item="{{ $category->id }}"
                    wire:key="category-{{ $category->id }}"
                    class="cursor-default pb-5"
                >
                    <div class="flex items-center justify-center rounded-md bg-gray-50 px-3 py-1.5 dark:bg-white/5">
                        <div class="flex flex-1 items-center" wire:sortable.handle>
                            <x-untitledui-dots-grid
                                class="mr-2 size-5 text-gray-400 dark:text-gray-500"
                                stroke-width="1.5"
                                aria-hidden="true"
                            />
                            <div class="flex items-center space-x-4">
                                <div
                                    class="{{ $category->is_enabled ? 'bg-green-600' : 'bg-gray-400 dark:bg-gray-900' }} h-2 w-2 shrink-0 rounded-full"
                                ></div>
                                <span class="text-sm font-medium leading-5 text-gray-900 dark:text-white">
                                    {{ $category->name }}
                                </span>
                            </div>
                        </div>
                        <span class="ml-4 text-xs leading-4 text-gray-500 dark:text-gray-400">
                            /{{ $category->slug }}
                        </span>
                    </div>
                    @if ($category->children->isNotEmpty())
                        <ul
                            wire:sortable-group.item-group="{{ $category->id }}"
                            class="ml-6 space-y-1.5 border-l border-dashed border-gray-200 pt-2 dark:border-white/10"
                        >
                            @foreach ($category->children as $child)
                                <li
                                    wire:key="sub-category-{{ $child->id }}"
                                    wire:sortable-group.item="{{ $child->id }}"
                                    class="-mx-1"
                                >
                                    <div
                                        class="flex flex-1 cursor-move items-center space-x-3 py-1.5"
                                        wire:sortable-group.handle
                                    >
                                        <div
                                            class="{{ $child->is_enabled ? 'bg-green-600' : 'bg-gray-400' }} h-2 w-2 shrink-0 rounded-full"
                                        ></div>
                                        <span class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                            {{ $child->name }}
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex shrink-0 justify-end space-x-4 p-4">
        <x-shopper::buttons.default wire:click="$dispatch('closePanel')" type="button">
            {{ __('shopper::forms.actions.close') }}
        </x-shopper::buttons.default>
    </div>
</div>
