<div x-data class="bg-white dark:bg-secondary-800 rounded-lg p-4 sm:p-6 shadow-md">
    <div class="pb-5 border-b border-secondary-200 dark:border-secondary-700 space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0">
        <div class="flex-1 min-w-0 max-w-2xl">
            <h2 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">{{ __('shopper::pages/products.attributes.title') }}</h2>
            <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-400 leading-5">
                {{ __('shopper::pages/products.attributes.description') }}
            </p>
        </div>
        <div>
            <x-shopper::buttons.primary wire:click="$emit('openModal', 'shopper-modals.add-product-attribute', {{ json_encode([$product->id]) }})" type="button">
                {{ __('shopper::pages/products.attributes.add') }}
            </x-shopper::buttons.primary>
        </div>
    </div>

    <dl class="divide-y divide-secondary-200 dark:divide-secondary-700">
        @forelse($productAttributes as $productAttribute)
            <div wire:key="product-attribute-{{ $productAttribute->id }}" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 @if(! $loop->first) sm:pt-5 @endif">
                <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                    {{ __($productAttribute->attribute->name) }}
                </dt>
                <dd class="flex space-x-4 text-sm leading-5 text-secondary-900 dark:text-white sm:mt-0 sm:col-span-2 {{ $productAttribute->model === 'multiple' ? 'grid': '' }}">
                    @if($productAttribute->model === 'single')
                        <div class="grow">
                            {{ str_limit(strip_tags(nl2br($productAttribute->values->first()->real_value)), 157) }}
                        </div>
                        <span class="shrink-0 flex items-start space-x-4">
                            <button wire:click="removeProductAttribute({{ $productAttribute->id }})" type="button" class="font-medium text-primary-600 hover:text-primary-500 transition duration-150 ease-in-out">
                                {{ __('shopper::layout.forms.actions.remove') }}
                            </button>
                        </span>
                    @else
                        <div>
                            <ul role="list" class="border border-secondary-200 rounded-md divide-y divide-secondary-200 dark:border-secondary-700 dark:divide-secondary-700">
                                @foreach($productAttribute->values as $pValue)
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                        <div class="w-0 flex-1 flex items-center">
                                            <span class="flex-1 w-0 truncate">
                                                {{ $pValue->real_value }}
                                            </span>
                                        </div>
                                        <div class="ml-4 shrink-0">
                                            <button wire:click="removeProductAttributeValue({{ $pValue->id }})" type="button" class="font-medium text-negative-600 hover:text-negative-500">
                                                <x-heroicon-o-trash class="h-5 w-5" />
                                            </button>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            <button wire:click="removeProductAttribute({{ $productAttribute->id }})" type="button" class="mt-3 w-full text-right font-medium text-primary-600 hover:text-primary-500 transition duration-150 ease-in-out">
                                {{ __('shopper::layout.forms.actions.remove_all') }}
                            </button>
                        </div>
                    @endif
                </dd>
            </div>
        @empty
            <div class="py-4 space-y-1 sm:py-6">
                <div class="flex justify-center items-center space-x-2">
                    <x-heroicon-o-clipboard-list class="h-8 w-8 text-secondary-400" />
                    <span class="font-medium py-8 text-secondary-400 dark:text-secondary-300 text-xl">
                        {{ __('shopper::pages/products.attributes.empty_values') }}
                    </span>
                </div>
            </div>
        @endforelse
    </dl>
</div>
