<div>
    @if($row->brand)
        <a href="{{ route('shopper.brands.edit', $row->brand) }}" class="inline-flex items-center border-b border-dashed border-primary-400 text-primary-500 hover:text-primary-400 hover:border-primary-300 font-medium text-sm leading-5">
            <span>{{ $row->brand->name }}</span>
        </a>
    @else
        <span class="inline-flex text-secondary-700 dark:text-secondary-500">&mdash;</span>
    @endif
</div>
