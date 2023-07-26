<x-shopper::container>
    @if($attributes->isNotEmpty())
        <div class="space-y-7 lg:grid lg:grid-cols-8 lg:gap-y-6 lg:gap-x-12 lg:space-y-0">
            <div class="lg:col-span-2">
                <aside class="sticky top-36">
                    <div class="space-y-5 flex flex-col">
                        @foreach($attributes as $attribute)
                            <a href="{{ route('shopper.attributes.edit', $attribute) }}" class="inline-flex items-center text-sm leading-5 text-secondary-600 hover:text-secondary-900 dark:text-secondary-400 dark:hover:text-white transition duration-200 ease-in-out">
                                @if($attribute->icon)
                                    <x-dynamic-component
                                        :component="$attribute->icon"
                                        class="h-5 w-5 text-secondary-400 dark:text-secondary-500 mr-2"
                                    />
                                @else
                                    <x-untitledui-octagon class="h-5 w-5 text-secondary-400 dark:text-secondary-500 mr-2" />
                                @endif
                                {{ $attribute->name }}
                                @if(! $attribute->is_enabled)
                                    <span class="inline-flex items-center ml-2.5 gap-x-1.5 rounded-full bg-danger-50 px-1.5 py-0.5 text-xxs font-medium text-danger-500 ring-1 ring-inset ring-danger-500/10 dark:bg-danger-800/20">
                                        <svg class="h-1.5 w-1.5 fill-danger-400" viewBox="0 0 6 6" aria-hidden="true">
                                            <circle cx="3" cy="3" r="3" />
                                        </svg>
                                        {{ __('shopper::layout.forms.actions.disabled') }}
                                    </span>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </aside>
            </div>
            <div class="lg:col-span-6 space-y-10">
                @foreach($attributes->where('is_enabled', true) as $attribute)
                    <div>
                        <h4 class="inline-flex items-center gap-x-2">
                            @if($attribute->icon)
                                <x-dynamic-component
                                    :component="$attribute->icon"
                                    class="h-6 w-6 text-secondary-500 dark:text-secondary-400"
                                    stroke-width="1.5"
                                />
                            @else
                                <x-untitledui-octagon
                                    class="h-6 w-6 text-secondary-500 dark:text-secondary-400"
                                    stroke-width="1.5"
                                />
                            @endif
                            <span class="text-secondary-900 dark:text-white font-display font-medium text-base leading-6">
                                {{ $attribute->name }}
                            </span>
                        </h4>

                        <x-shopper::card class="mt-2 space-y-2 divide-y divide-secondary-200 dark:divide-secondary-700 overflow-hidden">
                            @if($attribute->description)
                                <div class="p-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                    {{ $attribute->description }}
                                </div>
                            @endif

                            @if($attribute->hasMultipleValues())
                                <livewire:shopper-products.attributes.multiple-choice
                                    wire:key="attribute-multiple-{{  $attribute->id }}"
                                    :values="$attribute->values"
                                    :type="$attribute->type"
                                    :productId="$product->id"
                                    :attributeId="$attribute->id"
                                />
                            @endif
                        </x-shopper::card>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="flex flex-col justify-center items-center py-5">
            <x-untitledui-file-05 class="h-10 w-10 text-primary-500" stroke-width="1.5" />
            <h3 class="mt-2 text-lg font-medium font-display text-secondary-700 dark:text-secondary-300">
                {{ __('shopper::pages/products.attributes.empty_title') }}
            </h3>
            <span class="text-secondary-500 dark:text-secondary-400 text-base leading-5">
                {{ __('shopper::pages/products.attributes.empty_values') }}
            </span>
            <x-shopper::buttons.primary :link="route('shopper.attributes.create')" class="mt-4">
                {{ __('shopper::layout.forms.actions.create') }}
            </x-shopper::buttons.primary>
        </div>
    @endif
</x-shopper::container>
