<span>
    {{ $category->name }}
    @if ($category->parent)
        <span class="font-normal text-gray-500 dark:text-gray-400">
            {{ __('shopper::pages/categories.parent', ['parent' => $category->parent->name]) }}
        </span>
    @endif
</span>
