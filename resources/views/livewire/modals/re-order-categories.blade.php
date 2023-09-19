<x-shopper::modal
    headerClasses="bg-white dark:bg-secondary-800 p-4 sm:px-6 sm:py-4 border-b border-secondary-100 dark:border-secondary-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>
    <x-slot name="title">
        <x-heroicon-o-sort-ascending class="w-5 h-5 -ml-1 mr-3 text-secondary-400" />
        {{ __('shopper::words.reorder') }}
    </x-slot>

    <x-slot name="content">
        <div wire:sortable="updateGroupOrder" wire:sortable-group="updateCategoryOrder">
            @foreach($categories as $category)
                <div wire:sortable.item="{{ $category->id }}" wire:key="category-{{ $category->id }}" class="pb-5 cursor-move">
                    <div class="p-2 bg-secondary-100 dark:bg-secondary-700 flex items-center justify-center rounded-md">
                        <div wire:sortable.handle class="flex flex-1 items-center space-x-3">
                            <x-heroicon-o-view-grid class="shrink-0 w-5 h-5 text-secondary-300" />
                            <div class="flex items-center space-x-3">
                                <div class="shrink-0 w-2.5 h-2.5 rounded-full {{ $category->is_enabled ? 'bg-green-600': 'bg-secondary-400 dark:bg-secondary-900' }}"></div>
                                <span class="text-sm leading-5 text-secondary-900 dark:text-white font-medium">
                                    {{ $category->name }}
                                </span>
                            </div>
                        </div>
                        <span class="ml-4 text-secondary-500 dark:text-secondary-400 leading-4 text-xs">
                            /{{ $category->slug }}
                        </span>
                    </div>
                    @if($category->childs->isNotEmpty())
                        <ul wire:sortable-group.item-group="{{ $category->id }}" class="ml-4 border-l border-dashed border-secondary-200 dark:border-secondary-700">
                            @foreach($category->childs as $child)
                                <li wire:key="sub-category-{{ $child->id }}" wire:sortable-group.item="{{ $child->id }}" class="-mx-1">
                                    <div class="flex flex-1 items-center space-x-3 py-1 cursor-move">
                                        <div class="shrink-0 w-2 h-2 rounded-full {{ $child->is_enabled ? 'bg-green-600': 'bg-secondary-400' }}"></div>
                                        <span wire:sortable.handle class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ $child->name }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full rounded-md shadow-sm sm:w-auto">
            <x-shopper::buttons.default wire:click="$emit('closeModal')" type="button">
                {{ __('shopper::layout.forms.actions.close') }}
            </x-shopper::buttons.default>
        </span>
    </x-slot>
</x-shopper::modal>
