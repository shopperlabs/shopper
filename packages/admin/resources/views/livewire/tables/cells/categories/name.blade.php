<span>
    @if($category)
        {{ $category->name }}
        @if($category->parent_id)
            <span class="text-gray-500 font-normal dark:text-gray-400">
                {{ __('shopper::pages/categories.parent', ['parent' => $category->parent_name]) }}
            </span>
        @endif
    @endif
</span>
