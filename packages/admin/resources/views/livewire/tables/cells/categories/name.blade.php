<span>
    {{ $category->name }}
    @if ($category->parent)
        <span class="font-normal text-sh-fg-muted">
            {{ __('shopper::pages/categories.parent', ['parent' => $category->parent->name]) }}
        </span>
    @endif
</span>
