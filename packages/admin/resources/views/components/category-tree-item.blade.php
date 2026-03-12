@props([
    'category',
    'depth' => 0,
])

<li data-sort-item data-id="{{ $category->id }}">
    <div
        @class([
            'flex items-center shadow-xm mb-px rounded-xl border border-gray-200 bg-white px-2 py-3 dark:border-white/20 dark:bg-gray-800',
            'rounded-tl-none border-t-0' => $isFirstChild ?? false,
        ])
    >
        <div class="flex flex-1 items-center gap-2" data-sort-handle>
            <x-untitledui-dots-grid
                class="size-5 cursor-grab text-gray-300 dark:text-gray-600"
                aria-hidden="true"
            />
            <div
                @class([
                    'size-2 shrink-0 rounded-full',
                    'bg-success-600' => $category->is_enabled,
                    'bg-gray-400 dark:bg-gray-600' => ! $category->is_enabled,
                ])
            ></div>
            <span class="text-sm font-medium text-gray-900 dark:text-white">
                {{ $category->name }}
            </span>

            @if ($category->children->isNotEmpty())
                <button
                    type="button"
                    class="rounded p-0.5 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
                    x-on:click="$el.closest('[data-sort-item]').querySelector('[data-children]').classList.toggle('hidden')"
                >
                    <x-untitledui-chevron-down class="size-4" aria-hidden="true" />
                </button>
            @endif
        </div>
        <span class="ml-auto max-w-48 shrink-0 truncate text-xs text-gray-500 dark:text-gray-400">/{{ $category->slug }}</span>
    </div>

    <ul
        data-children
        x-data="nestedSortable({ parentId: '{{ $category->id }}' })"
        class="pl-6"
    >
        @foreach ($category->children->sortBy('position') as $child)
            <x-shopper::category-tree-item :category="$child" :depth="$depth + 1" :is-first-child="$loop->first" />
        @endforeach
    </ul>
</li>
