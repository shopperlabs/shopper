<div>
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ __('Similar Products') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ __('All products that can be identified as similar or complementary to your product.') }}
        </p>
    </div>

    <section aria-labelledby="similar_products_heading">
        <div class="mt-5 bg-white dark:bg-gray-800 p-4 sm:p-6 shadow rounded-md">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-5">
                    <x-shopper-input.select aria-label="{{ __('Products') }}" name="from[]" id="multiselect" size="8" multiple>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </x-shopper-input.select>
                </div>

                <div class="col-span-2 flex items-center">
                    <div class="space-y-2 flex-1">
                        <button type="button" id="multiselect_rightSelected" class="inline-flex w-full justify-center items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-blue-200 transition ease-in-out duration-150">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button type="button" id="multiselect_leftSelected" class="inline-flex w-full justify-center items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-blue-200 transition ease-in-out duration-150">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="col-span-5">
                    <x-shopper-input.select aria-label="{{ __('Similar Products') }}" name="to[]" id="multiselect_to" size="8" multiple>
                        @foreach($relatedProducts as $related)
                            <option value="{{ $related->id }}">{{ $related->name }}</option>
                        @endforeach
                    </x-shopper-input.select>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://unpkg.com/multiselect-two-sides@2.5.7/dist/js/multiselect.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#multiselect').multiselect({
                afterMoveToRight: function($left, $right, $options) {
                    @this.add($options[0].value);
                },
                afterMoveToLeft: function($left, $right, $options) {
                    @this.remove($options[0].value);
                }
            });
        });
    </script>
@endpush
