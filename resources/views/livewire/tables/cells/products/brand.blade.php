<div>
    @if($brand)
        <a href="{{ route('shopper.brands.edit', $brand) }}" class="inline-flex items-center border-b border-dashed border-primary-400 text-primary-500 hover:text-primary-400 hover:border-primary-300 font-medium text-sm leading-5">
            <span>{{ $brand->name }}</span>
        </a>
    @else
        <span class="inline-flex text-gray-700 dark:text-gray-500">&mdash;</span>
    @endif
</div>
