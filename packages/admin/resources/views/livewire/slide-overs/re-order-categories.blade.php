<div class="flex h-full flex-col divide-y divide-gray-200 dark:divide-gray-700">
    <div class="h-0 flex-1 overflow-y-auto py-6">
        <header class="px-4 sm:px-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center">
                    <x-untitledui-switch-vertical class="mr-2 w-5 h-5 text-gray-400" aria-hidden="true" />
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ __('shopper::words.reorder') }}
                    </h2>
                </div>
                <div class="ml-3 flex h-7 items-center">
                    <button
                        type="button"
                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 dark:bg-gray-900 dark:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:ring-offset-gray-900"
                        wire:click="$dispatch('closePanel')"
                    >
                        <span class="sr-only">Close panel</span>
                        <x-untitledui-x class="h-6 w-6" stroke-width="1.5" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </header>

        <div wire:sortable="updateGroupOrder" wire:sortable-group="updateCategoryOrder" class="mt-8 flex-1 px-4 sm:px-6">
            @foreach($categories as $category)
                <div wire:sortable.item="{{ $category->id }}" wire:key="category-{{ $category->id }}" class="pb-5 cursor-default">
                    <div class="flex items-center justify-center px-3 py-1.5 bg-gray-50 dark:bg-white/5 rounded-md">
                        <div class="flex flex-1 items-center" wire:sortable.handle>
                            <x-untitledui-dots-grid
                                class="w-5 h-5 mr-2 text-gray-400 dark:text-gray-500"
                                stroke-width="1.5"
                                aria-hidden="true"
                            />
                            <div class="flex items-center space-x-4">
                                <div class="shrink-0 w-2 h-2 rounded-full {{ $category->is_enabled ? 'bg-green-600': 'bg-gray-400 dark:bg-gray-900' }}"></div>
                                <span class="text-sm leading-5 text-gray-900 dark:text-white font-medium">
                                    {{ $category->name }}
                                </span>
                            </div>
                        </div>
                        <span class="ml-4 text-gray-500 dark:text-gray-400 leading-4 text-xs">
                            /{{ $category->slug }}
                        </span>
                    </div>
                    @if($category->children->isNotEmpty())
                        <ul wire:sortable-group.item-group="{{ $category->id }}" class="ml-6 pt-2 space-y-1.5 border-l border-dashed border-gray-200 dark:border-gray-700">
                            @foreach($category->children as $child)
                                <li wire:key="sub-category-{{ $child->id }}" wire:sortable-group.item="{{ $child->id }}" class="-mx-1">
                                    <div class="flex flex-1 items-center space-x-3 py-1.5 cursor-move" wire:sortable-group.handle>
                                        <div class="shrink-0 w-2 h-2 rounded-full {{ $child->is_enabled ? 'bg-green-600': 'bg-gray-400' }}"></div>
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

    <div class="flex shrink-0 justify-end p-4 space-x-4">
        <x-shopper::buttons.default wire:click="$dispatch('closePanel')" type="button">
            {{ __('shopper::layout.forms.actions.close') }}
        </x-shopper::buttons.default>
    </div>
</div>
