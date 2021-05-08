<x-shopper-modal
    headerClasses="bg-white p-4 sm:px-6 sm:py-4 border-b border-gray-100"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>
    <x-slot name="title">
        <svg class="w-5 h-5 -ml-1 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
        </svg>
        {{ __("Reorder categories") }}
    </x-slot>

    <x-slot name="content">
        <div wire:sortable="updateGroupOrder" wire:sortable-group="updateCategoryOrder">
            @foreach($categories as $category)
                <div wire:sortable.item="{{ $category->id }}" wire:key="category-{{ $category->id }}" class="pb-5 cursor-move">
                    <div class="p-2 bg-gray-100 flex items-center justify-center rounded-md">
                        <div wire:sortable.handle class="flex flex-1 items-center space-x-3">
                            <svg class="flex-shrink-0 w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-2.5 h-2.5 rounded-full {{ $category->is_enabled ? 'bg-green-600': 'bg-gray-400' }}"></div>
                                <span class="text-sm leading-5 text-gray-900 font-medium">{{ $category->name }}</span>
                            </div>
                        </div>
                        <span class="ml-4 text-gray-500 leading-4 text-xs">
                            /{{ $category->slug }}
                        </span>
                    </div>
                    @if($category->childs->isNotEmpty())
                        <ul wire:sortable-group.item-group="{{ $category->id }}" class="ml-4 border-l border-dashed border-gray-200">
                            @foreach($category->childs as $child)
                                <li wire:key="sub-category-{{ $child->id }}" wire:sortable-group.item="{{ $child->id }}" class="-mx-1">
                                    <div class="flex flex-1 items-center space-x-3 py-1 cursor-move">
                                        <div class="flex-shrink-0 w-2 h-2 rounded-full {{ $child->is_enabled ? 'bg-green-600': 'bg-gray-400' }}"></div>
                                        <span wire:sortable.handle class="text-sm leading-5 text-gray-600">{{ $child->name }}</span>
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
            <x-shopper-default-button wire:click="$emit('closeModal')" type="button">
                {{ __('Close') }}
            </x-shopper-default-button>
        </span>
    </x-slot>
</x-shopper-modal>
