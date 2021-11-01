<div class="flex items-center">
    @if($product->variations_count > 0)
        <span class="mr-2 text-xs px-1.5 inline-flex leading-5 font-semibold rounded-full {{ $product->variationsStock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                {{ $product->variationsStock }}
            </span>
        {{ __('in stock for :count variant(s)', ['count' => $product->variations_count]) }}
    @else
        <span class="mr-2 text-xs px-1.5 inline-flex leading-5 font-semibold rounded-full {{ $product->stock < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                {{ $product->stock }}
            </span>
        {{ __('in stock') }}
    @endif
</div>
