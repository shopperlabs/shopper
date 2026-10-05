<span>
    {{ $category->name }}
    @if ($category->parent)
        <span class="text-sh-fg-muted font-normal">
            {{ __('shopper::pages/categories.parent', ['parent' => $category->parent->name]) }}
        </span>
    @endif
</span>
