<div>
    <x-shopper::breadcrumb back="shopper.products.index">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper::breadcrumb.link :link="route('shopper.products.index')" title="shopper::layout.sidebar.products" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-3">
        <x-slot name="title">
            {{ __('shopper::messages.actions_label.add_new', ['name' => 'product']) }}
        </x-slot>

        <x-slot name="action">
            <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </x-slot>
    </x-shopper::heading>

    <div class="mt-6 space-y-5 lg:grid lg:grid-cols-6 lg:gap-6">
        <div class="lg:col-span-4 space-y-5">
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow p-4 sm:p-5">
                <div>
                    <x-shopper::forms.group label="shopper::layout.forms.label.name" for="name" isRequired :error="$errors->first('name')">
                        <x-shopper::forms.input wire:model.defer="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Apple, Nike, Samsung...') }}" />
                    </x-shopper::forms.group>
                </div>
                <div class="mt-5 border-t border-secondary-200 dark:border-secondary-700 pt-4">
                    <x-shopper::forms.group label="shopper::layout.forms.label.description" for="description">
                        <livewire:shopper-forms.trix :value="$description" />
                    </x-shopper::forms.group>
                </div>
            </div>
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow p-4 sm:p-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('shopper::messages.media') }}</h4>
                <div class="mt-4">
                    <livewire:shopper-forms.uploads.multiple />
                </div>
            </div>
            <div class="relative bg-white dark:bg-secondary-800 rounded-lg shadow pt-4 sm:pt-5 overflow-hidden">
                <div class="flex items-center justify-between px-4 sm:px-5">
                    <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('shopper::messages.pricing') }}</h4>
                    <div x-data="{ display: false }">
                        <button @click="display = true" x-tooltip.raw="{{ __('shopper::pages/products.about_pricing') }}" type="button" class="inline-flex text-sm text-secondary-500 hover:text-secondary-600 dark:text-secondary-400 dark:hover:text-secondary-300">
                            <x-heroicon-o-question-mark-circle class="h-5 w-5" />
                        </button>
                        <div x-show="display" @click.outside="display = false" class="absolute z-30 top-4 inset-x-0 p-4 mx-4 rounded-md bg-secondary-50 border border-secondary-100 dark:bg-secondary-700 dark:border-gray-600">
                            <div class="flex">
                                <div>
                                    <p class="text-sm font-medium text-secondary-700 dark:text-secondary-300">
                                        {{ __('shopper::pages/products.about_pricing_content') }}
                                    </p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <div class="-mx-1.5 -my-1.5">
                                        <button @click="display = false" type="button" class="inline-flex bg-secondary-50 dark:bg-secondary-700 rounded-md p-1 text-secondary-500 dark:text-secondary-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-primary-50 focus:ring-primary-600">
                                            <span class="sr-only">Dismiss</span>
                                            <x-heroicon-o-x class="h-5 w-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="grid gap-4 sm:grid-cols-6 sm:gap-6 p-4 sm:p-5">
                        <div class="col-span-6 sm:col-span-3">
                            <x-inputs.currency
                                :label="__('shopper::layout.forms.label.price_amount')"
                                placeholder="0.00"
                                wire:model.defer="price_amount"
                                class="dark:bg-secondary-700 dark:border-transparent dark:focus:border-primary-500 dark:text-white"
                                min="0"
                                thousands=","
                                decimal="."
                                :suffix="$currency"
                            />
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-inputs.currency
                                :label="__('shopper::layout.forms.label.compare_price')"
                                placeholder="0.00"
                                wire:model.defer="old_price_amount"
                                class="dark:bg-secondary-700 dark:border-transparent dark:focus:border-primary-500 dark:text-white"
                                min="0"
                                thousands=","
                                decimal="."
                                :suffix="$currency"
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 p-4 sm:p-5">
                        <div class="col-span-6 sm:col-span-3">
                            <x-inputs.currency
                                :label="__('shopper::layout.forms.label.cost_per_item')"
                                placeholder="0.00"
                                wire:model.defer="cost_amount"
                                class="dark:bg-secondary-700 dark:border-transparent dark:focus:border-primary-500 dark:text-white"
                                min="0"
                                thousands=","
                                decimal="."
                                :suffix="$currency"
                                :hint="__('shopper::pages/products.cost_per_items_help_text')"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow pt-4 sm:pt-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white px-4 sm:px-5">{{ __('shopper::messages.inventory') }}</h4>
                <div class="divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 p-4 sm:p-5">
                        <div class="sm:col-span-1">
                            <x-shopper::forms.group label="shopper::layout.forms.label.sku" for="sku" :error="$errors->first('sku')">
                                <x-shopper::forms.input wire:model.defer="sku" id="sku" type="text" autocomplete="off" />
                            </x-shopper::forms.group>
                        </div>
                        <div class="sm:col-span-1">
                            <x-shopper::forms.group label="shopper::layout.forms.label.barcode" for="barcode" :error="$errors->first('barcode')">
                                <x-shopper::forms.input wire:model.defer="barcode" id="barcode" type="text" autocomplete="off" />
                            </x-shopper::forms.group>
                            @if($barcodeImage)
                                <div class="mt-2 rounded-sm w-auto shrink-0">
                                    {!! $barcodeImage !!}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6 p-4 sm:p-5">
                        @if($inventories->count() <= 1)
                            <div class="sm:col-span-1">
                                <x-shopper::forms.group label="shopper::layout.forms.label.quantity" for="quantity">
                                    <x-shopper::forms.input wire:model.defer="quantity.{{ $inventories->first()->id }}" id="quantity" type="number" min="0" autocomplete="off" />
                                </x-shopper::forms.group>
                            </div>
                        @endif
                        <div class="sm:col-span-1">
                            <x-shopper::forms.group label="shopper::layout.forms.label.safety_stock" for="security_stock" helpText="shopper::pages/products.safety_security_help_text">
                                <x-shopper::forms.input wire:model.defer="securityStock" id="security_stock" type="number" min="1" step="1" autocomplete="off" />
                            </x-shopper::forms.group>
                        </div>
                    </div>
                    @if($inventories->count() > 1)
                        <div class="p-4 sm:p-5">
                            <div class="flex items-center justify-between">
                                <h4 class="block text-sm font-medium leading-5 text-secondary-900 dark:text-white">{{ __('shopper::pages/products.quantity_inventory') }}</h4>
                                <a href="{{ route('shopper.settings.inventories.index') }}" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-primary-600 hover:text-primary-800">
                                    {{ __('shopper::pages/products.manage_inventories') }}
                                </a>
                            </div>
                            <div class="mt-4 divide-y divide-secondary-200 dark:divide-secondary-700">
                                <div class="grid grid-cols-3 py-4">
                                    <div class="col-span-2">
                                        <span class="text-sm leading-5 font-semibold text-secondary-900 dark:text-white uppercase">{{ __('shopper::pages/products.inventory_name') }}</span>
                                    </div>
                                    <div class="col-span-1 pl-4 flex justify-end">
                                        <span class="text-sm leading-5 font-semibold text-secondary-900 dark:text-white uppercase">{{ __('shopper::messages.available') }}</span>
                                    </div>
                                </div>
                                @foreach($inventories as $inventory)
                                    <div class="grid grid-cols-3 py-4" wire:key="inventory-{{ $inventory->id }}">
                                        <div class="col-span-2">
                                            <span class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ $inventory->name }}</span>
                                        </div>
                                        <div class="col-span-1 pl-4 flex justify-end">
                                            <x-shopper::forms.input
                                                wire:model.defer="quantity.{{ $inventory->id }}"
                                                wire:key="inventory-{{ $inventory->id }}"
                                                type="number"
                                                class="block w-24"
                                                aria-label="{{ __('shopper::pages/products.quantity_inventory') }}"
                                                min="0"
                                                autocomplete="off"
                                            />
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow overflow-hidden divide-y divide-secondary-200 dark:divide-secondary-700">
                <div class="p-4 sm:p-5">
                    <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('shopper::messages.shipping') }}</h4>
                    <div class="mt-5 space-y-4">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <x-shopper::forms.checkbox wire:model.defer="backorder" id="backorder" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <x-shopper::label for="backorder" :value="__('shopper::pages/products.product_can_returned')" />
                                <p class="text-secondary-500 dark:text-secondary-400">{{ __('shopper::pages/products.product_can_returned_help_text') }}</p>
                            </div>
                        </div>
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <x-shopper::forms.checkbox wire:model.lazy="requiresShipping" id="required_shipping" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <x-shopper::label for="required_shipping" :value="__('shopper::pages/products.product_shipped')" />
                                <p class="text-secondary-500 dark:text-secondary-400">{{ __('shopper::pages/products.product_shipped_help_text') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @if($requiresShipping)
                    <div class="p-4 sm:p-5">
                        <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('shopper::messages.weight_dimension') }}</h4>
                        <p class="text-sm text-secondary-500 dark:text-secondary-400 leading-5">{{ __('shopper::pages/products.weight_dimension_help_text') }}</p>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2 sm:gap-6 sm:gap-y-4">
                            <x-shopper::forms.group class="sm:col-span-1" label="shopper::layout.forms.label.width">
                                <x-shopper::forms.input wire:model.defer="widthValue" id="WidthValue" type="text" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper::forms.select wire:model.defer="WidthUnit" aria-label="{{ __('shopper::layout.forms.label.width_unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="cm">cm</option>
                                        <option value="m">m</option>
                                    </x-shopper::forms.select>
                                </div>
                            </x-shopper::forms.group>
                            <x-shopper::forms.group class="sm:col-span-1" label="shopper::layout.forms.label.height">
                                <x-shopper::forms.input wire:model.defer="heightValue" id="heightValue" type="text" class="pl-3 pr-12" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper::forms.select wire:model.defer="heightUnit" aria-label="{{ __('shopper::layout.forms.label.height_unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="cm">cm</option>
                                        <option value="m">m</option>
                                    </x-shopper::forms.select>
                                </div>
                            </x-shopper::forms.group>
                            <x-shopper::forms.group class="sm:col-span-1" label="shopper::layout.forms.label.weight">
                                <x-shopper::forms.input wire:model.defer="weightValue" id="weightValue" type="text" class="block w-full pl-3 pr-12" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper::forms.select wire:model.defer="weightUnit" aria-label="{{ __('shopper::layout.forms.label.weight_unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="kg">kg</option>
                                        <option value="g">g</option>
                                    </x-shopper::forms.select>
                                </div>
                            </x-shopper::forms.group>
                            <x-shopper::forms.group class="sm:col-span-1" label="shopper::layout.forms.label.volume">
                                <x-shopper::forms.input wire:model.defer="volumeValue" id="VolumeValue" type="text" class="block w-full pl-3 pr-12" placeholder="0" />
                                <div class="absolute inset-y-0 right-0 flex items-center">
                                    <x-shopper::forms.select wire:model.defer="VolumeUnit" aria-label="{{ __('shopper::layout.forms.label.volume_unit') }}" class="py-0 pl-2 pr-7 border-transparent bg-transparent">
                                        <option value="l">l</option>
                                        <option value="ml">ml</option>
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
                <div class="bg-white dark:bg-secondary-800 rounded-lg shadow divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="p-4 sm:p-5">
                        <x-shopper::label value="{{ __('shopper::pages/products.status') }}" />
                        <div class="mt-4 px-3 py-2.5 bg-primary-500 bg-opacity-10 rounded-md text-primary-600 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="h-8 w-8 flex items-center justify-center rounded-md bg-primary-600 shrink-0">
                                    <x-heroicon-o-eye class="h-5 w-5 text-white" />
                                </span>
                                <span class="font-semibold ml-3 text-sm">{{ __('shopper::layout.forms.label.visible') }}</span>
                            </div>
                            <div>
                                <span wire:model="isVisible"
                                      x-data="{ on: @entangle('isVisible') }"
                                      role="checkbox"
                                      tabindex="0"
                                      @click="$dispatch('input', !on); on = !on"
                                      @keydown.space.prevent="on = !on"
                                      :aria-checked="on.toString()"
                                      aria-checked="false"
                                      :class="{ 'bg-secondary-200 dark:bg-secondary-600': !on, 'bg-primary-600': on }"
                                      class="relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer focus:outline-none focus:shadow-outline-primary bg-secondary-200">
                                    <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                    <span aria-hidden="true"
                                          :class="{ 'translate-x-5': on, 'translate-x-0': !on }"
                                          class="inline-block h-5 w-5 rounded-full bg-white shadow transform translate-x-0"
                                    ></span>
                                </span>
                            </div>
                        </div>
                        <p class="mt-2 text-secondary-500 dark:text-secondary-400 leading-5 text-sm">
                            {{ __('shopper::pages/products.visible_help_text') }}
                        </p>
                    </div>
                    <div class="p-4 sm:p-5">
                        <x-datetime-picker
                            :label="__('shopper::layout.forms.label.availability')"
                            :placeholder="__('shopper::layout.forms.placeholder.pick_a_date')"
                            wire:model="publishedAt"
                            parse-format="YYYY-MM-DD HH:mm"
                            time-format="24"
                            without-timezone
                            class="dark:bg-secondary-700"
                        />
                        @if($publishedAt)
                            <div class="mt-2 flex items-start">
                                <div class="mt-1 shrink-0 w-2.5 h-2.5 rounded-full bg-primary-600"></div>
                                <p class="ml-2.5 text-sm text-secondary-500 leading-5">
                                    {{ __('shopper::messages.published_on') }} <br>
                                    {{ $publishedAtFormatted }}
                                </p>
                            </div>
                        @else
                            <p class="mt-2 text-sm leading-5 text-secondary-500">
                                {{ __('shopper::pages/products.availability_description') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="bg-white dark:bg-secondary-800 rounded-lg shadow">
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('shopper::pages/products.product_associations') }}</h4>
                    </div>
                    <div class="divide-y divide-secondary-200 dark:divide-secondary-700" wire:ignore>
                        <x-shopper::forms.group class="p-4 sm:p-5 z-30" label="shopper::layout.forms.label.brand" for="brand_id">
                            <x-shopper::forms.select wire:model.defer="selectedBrand" id="brand_id" x-data="{}" x-init="function () { choices($el) }">
                                <option value="0">{{ __('shopper::pages/brands.empty_brand') }}</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" @selected($brand->id === $brand_id)>{{ $brand->name }}</option>
                                @endforeach
                            </x-shopper::forms.select>
                        </x-shopper::forms.group>
                        <x-shopper::forms.group class="p-4 sm:p-5" label="shopper::layout.sidebar.collections" for="collection_ids">
                            <x-select wire:model.defer="collection_ids" id="collection_ids" multiselect>
                                @foreach($collections as $collection)
                                    <x-select.user-option
                                        :src="$collection->getFirstMediaUrl(config('shopper.system.storage.disks.uploads'))"
                                        :label="$collection->name"
                                        :value="$collection->id"
                                    />
                                @endforeach
                            </x-select>
                        </x-shopper::forms.group>
                    </div>
                </div>
                <div class="bg-white dark:bg-secondary-800 rounded-lg shadow">
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('shopper::pages/products.product_categories') }}</h4>
                    </div>
                    <div class="px-4 py-3">
                        <div class="space-y-3 p-2 max-h-96 border border-dashed border-secondary-200 rounded-md shadow-sm overflow-scroll hide-scroll dark:border-secondary-700">
                            @forelse($categories as $category)
                                <div @if($category->children->isNotEmpty()) x-data="{ display: true }" @endif class="space-y-3">
                                    <div class="relative flex justify-between">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <x-shopper::forms.checkbox id="category-{{ $category->id }}" wire:model.defer="category_ids" value="{{ $category->id }}" />
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="category-{{ $category->id }}" class="font-medium text-secondary-700 dark:text-secondary-300">{{ $category->name }}</label>
                                            </div>
                                        </div>
                                        @if($category->children->isNotEmpty())
                                            <button type="button" @click="display = !display" class="text-secondary-500 dark:text-secondary-400" aria-expanded="true" x-bind:aria-expanded="display.toString()">
                                                <x-heroicon-o-plus-sm x-show="!display" class="h-5 w-5" />
                                                <x-heroicon-o-minus-sm x-show="display" class="h-5 w-5" />
                                            </button>
                                        @endif
                                    </div>

                                    @if($category->children->isNotEmpty())
                                        <div x-show="display" class="ml-4 space-y-3">
                                            @foreach($category->children as $child)
                                                @include('shopper::components.forms.checkbox-category', ['parent' => $category->parent_id, 'category' => $child])
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <x-heroicon-o-tag class="mx-auto h-12 w-12 text-secondary-400 dark:text-secondary-500" />
                                    <h3 class="mt-2 text-sm font-medium text-secondary-900 dark:text-white">{{ __('shopper::pages/products.no_category') }}</h3>
                                    <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-400">{{ __('shopper::pages/products.no_category_text') }}</p>
                                    <div class="mt-6">
                                        <x-shopper::buttons.primary :link="route('shopper.categories.create')">
                                            <x-heroicon-s-plus class="-ml-1 mr-2 h-5 w-5"/>
                                            {{ __('shopper::pages/products.new_category') }}
                                        </x-shopper::buttons.primary>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div class="mt-6 border-t border-secondary-200 dark:border-secondary-700 pt-5 pb-10">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper::loader wire:loading wire:target="store" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</div>
