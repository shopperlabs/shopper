<x-shopper::container>

    <div class="mt-8 space-y-6 lg:grid lg:grid-cols-6 lg:gap-y-6 lg:gap-x-12 lg:space-y-0">
        <div class="lg:col-span-4 space-y-8">
            <div>
                <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white">
                    {{ __('shopper::words.location') }}
                </h4>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 py-5">
                        <div class="sm:col-span-1">
                            <x-shopper::forms.group :label="__('shopper::layout.forms.label.barcode')" for="barcode" :error="$errors->first('barcode')">
                                <x-shopper::forms.input wire:model.defer="barcode" id="barcode" type="text" class="dark:bg-gray-800" autocomplete="off" />
                            </x-shopper::forms.group>
                            @if($barcodeImage)
                                <div class="mt-2 rounded-sm w-auto shrink-0">
                                    {!! $barcodeImage !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <div class="pb-5">
                    <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white">
                        {{ __('shopper::words.shipping') }}
                    </h4>
                    <div class="mt-5 space-y-4">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <x-shopper::forms.checkbox wire:model.defer="backorder" id="backorder" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <x-shopper::label for="backorder" :value="__('shopper::pages/products.product_can_returned')" />
                                <p class="text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::pages/products.product_can_returned_help_text') }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <x-shopper::forms.checkbox wire:model.lazy="requireShipping" id="required_shipping" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <x-shopper::label for="required_shipping" :value="__('shopper::pages/products.product_shipped')" />
                                <p class="text-gray-500 dark:text-gray-400">
                                    {{ __('shopper::pages/products.product_shipped_help_text') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @if($requireShipping)
                    <div class="py-5">
                        <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white">
                            {{ __('shopper::words.weight_dimension') }}
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 leading-5">
                            {{ __('shopper::pages/products.weight_dimension_help_text') }}
                        </p>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2 sm:gap-6 sm:gap-y-4">
                            <x-shopper::forms.group class="sm:col-span-1" :label="__('shopper::layout.forms.label.width')">
                                <x-shopper::forms.input wire:model.defer="widthValue" id="WidthValue" type="text" class="dark:bg-gray-800" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper::forms.select
                                        wire:model.defer="WidthUnit"
                                        aria-label="{{ __('shopper::layout.forms.label.width_unit') }}"
                                        class="py-0 pl-2 pr-7 border-y-gray-300 dark:bg-gray-800"
                                    >
                                        <option value="cm">{{ __('shopper::words.unity.cm') }}</option>
                                        <option value="m">{{ __('shopper::words.unity.m') }}</option>
                                    </x-shopper::forms.select>
                                </div>
                            </x-shopper::forms.group>
                            <x-shopper::forms.group class="sm:col-span-1" :label="__('shopper::layout.forms.label.height')">
                                <x-shopper::forms.input wire:model.defer="heightValue" id="heightValue" type="text" class="pl-3 pr-12 dark:bg-gray-800" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper::forms.select
                                        wire:model.defer="heightUnit"
                                        aria-label="{{ __('shopper::layout.forms.label.height_unit') }}"
                                        class="py-0 pl-2 pr-7 border-y-gray-300 dark:bg-gray-800"
                                    >
                                        <option value="cm">{{ __('shopper::words.unity.cm') }}</option>
                                        <option value="m">{{ __('shopper::words.unity.m') }}</option>
                                    </x-shopper::forms.select>
                                </div>
                            </x-shopper::forms.group>
                            <x-shopper::forms.group class="sm:col-span-1" :label="__('shopper::layout.forms.label.weight')">
                                <x-shopper::forms.input wire:model.defer="weightValue" id="weightValue" type="text" class="block w-full pl-3 pr-12 dark:bg-gray-800" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper::forms.select
                                        wire:model.defer="weightUnit"
                                        aria-label="{{ __('shopper::layout.forms.label.weight_unit') }}"
                                        class="py-0 pl-2 pr-7 border-y-gray-300 dark:bg-gray-800"
                                    >
                                        <option value="kg">{{ __('shopper::words.unity.kg') }}</option>
                                        <option value="g">{{ __('shopper::words.unity.g') }}</option>
                                    </x-shopper::forms.select>
                                </div>
                            </x-shopper::forms.group>
                            <x-shopper::forms.group class="sm:col-span-1" :label="__('shopper::layout.forms.label.volume')">
                                <x-shopper::forms.input
                                    wire:model.defer="volumeValue"
                                    id="VolumeValue"
                                    type="text"
                                    class="block w-full pl-3 pr-12 dark:bg-gray-800"
                                    placeholder="0"
                                />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper::forms.select
                                        wire:model.defer="VolumeUnit"
                                        aria-label="{{ __('shopper::layout.forms.label.volume_unit') }}"
                                        class="py-0 pl-2 pr-7 border-y-gray-300 dark:bg-gray-800"
                                    >
                                        <option value="l">{{ __('shopper::words.unity.l') }}</option>
                                        <option value="ml">{{ __('shopper::words.unity.ml') }}</option>
                                    </x-shopper::forms.select>
                                </div>
                            </x-shopper::forms.group>
                        </div>
                    </div>
                @endif
            </div>

            <x-shopper::forms.seo
                slug="products"
                :title="$seoTitle"
                :url="str_slug($name)"
                :description="$seoDescription"
                :canUpdate="$updateSeo"
            />
        </div>
        <div class="lg:col-span-2">
            <aside class="space-y-5">
                <x-shopper::card>
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white">
                            {{ __('shopper::pages/products.product_associations') }}
                        </h4>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700" wire:ignore>
                        <x-shopper::forms.group class="p-4 sm:p-5" :label="__('shopper::layout.sidebar.collections')" for="collection_ids">
                            <x-select wire:model.defer="collection_ids" id="collection_ids" multiselect>
                                @foreach($collections as $collection)
                                    <x-select.user-option
                                        :src="$collection->getFirstMediaUrl(config('shopper.core.storage.collection_name'))"
                                        :label="$collection->name"
                                        :value="$collection->id"
                                    />
                                @endforeach
                            </x-select>
                        </x-shopper::forms.group>
                    </div>
                </x-shopper::card>
            </aside>
        </div>
    </div>
</x-shopper::container>
