<span>
    {{ $category->name }}
    @if($category->parent)
        <span class="text-gray-500 font-normal dark:text-gray-400">
            {{ __('shopper::pages/categories.parent', ['parent' => $category->parent->name]) }}
        </span>
    @endif
</span>
