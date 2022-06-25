<div>
    <div class="grid gap-4 mt-6 xl:grid-cols-6 lg:gap-6">
        <div class="space-y-5 xl:col-span-4">
            <div class="p-4 bg-white rounded-lg shadow dark:bg-secondary-800 sm:p-5">
                <div>
                    <x-shopper::forms.group label="shopper::layout.forms.label.name" for="name" isRequired :error="$errors->first('name')">
                        <x-shopper::forms.input wire:model.defer="name" id="name" type="text" autocomplete="off" placeholder="Apple, Nike, Samsung..." />
                    </x-shopper::forms.group>
                </div>
                <div class="pt-4 mt-5 border-t border-secondary-200 dark:border-secondary-700">
                    <x-shopper::forms.group label="shopper::layout.forms.label.description" for="description">
                        <livewire:shopper-forms.trix :value="$description" />
                    </x-shopper::forms.group>
                </div>
            </div>
            <div class="p-4 overflow-hidden bg-white rounded-lg shadow dark:bg-secondary-800 sm:p-5">
                <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('shopper::messages.media') }}</h4>
                <div class="mt-4">
                    <x-shopper::forms.filepond
                        wire:model="files"
                        multiple
                        allowImagePreview
                        imagePreviewMaxHeight="200"
                        allowFileTypeValidation
                        allowFileSizeValidation
                        maxFileSize="5mb"
                        :images="$images"
                    />
                    @error('files.*')
                        <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="pt-4 overflow-hidden bg-white rounded-lg shadow dark:bg-secondary-800 sm:pt-5">
                <h4 class="block px-4 text-base font-medium leading-6 text-secondary-900 dark:text-white sm:px-5">{{ __('shopper::messages.pricing') }}</h4>
                <div class="divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="grid gap-4 p-4 sm:grid-cols-6 sm:gap-6 sm:p-5">
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
        </div>
        <div class="xl:col-span-2">
            <aside class="space-y-5">
                <div class="bg-white divide-y rounded-lg shadow dark:bg-secondary-800 divide-secondary-200 dark:divide-secondary-700">
                    <div class="p-4 sm:p-5">
                        <x-shopper::label value="{{ __('shopper::pages/products.status') }}" />
                        <div class="mt-4 px-3 py-2.5 bg-primary-500 bg-opacity-10 rounded-md text-primary-600 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="flex items-center justify-center w-8 h-8 rounded-md bg-primary-600 shrink-0">
                                    <x-heroicon-o-eye class="w-5 h-5 text-white" />
                                </span>
                                <span class="ml-3 text-sm font-semibold">{{ __('shopper::layout.forms.label.visible') }}</span>
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
                                      class="relative inline-flex h-6 transition-colors duration-200 ease-in-out border-2 border-transparent rounded-full cursor-pointer shrink-0 w-11 focus:outline-none focus:shadow-outline-primary bg-secondary-200">
                                    <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                    <span aria-hidden="true"
                                          :class="{ 'translate-x-5': on, 'translate-x-0': !on }"
                                          class="inline-block w-5 h-5 transition duration-200 ease-in-out transform translate-x-0 bg-white rounded-full shadow"
                                    ></span>
                                </span>
                            </div>
                        </div>
                        <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                            {{ __('shopper::pages/products.visible_help_text') }}
                        </p>
                    </div>
                    <div class="p-4 sm:p-5">
                        <x-datetime-picker
                            :label="__('shopper::layout.forms.label.availability')"
                            :placeholder="__('shopper::layout.forms.placeholder.pick_a_date')"
                            wire:model.lazy="publishedAt"
                            parse-format="YYYY-MM-DD HH:mm"
                            time-format="24"
                            without-timezone
                            class="dark:bg-secondary-700"
                        />
                        @if($publishedAt)
                            <div class="flex items-start mt-2">
                                <div class="mt-1 shrink-0 w-2.5 h-2.5 rounded-full bg-primary-600"></div>
                                <p class="ml-2.5 text-sm text-secondary-500 dark:text-secondary-400 leading-5">
                                    {{ __('shopper::messages.published_on') }} <br>
                                    {{ $publishedAtFormatted }}
                                </p>
                            </div>
                        @else
                            <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::pages/products.availability_description') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow dark:bg-secondary-800">
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('shopper::pages/products.product_associations') }}</h4>
                    </div>
                    <div class="divide-y divide-secondary-200 dark:divide-secondary-700" wire:ignore>
                        <x-shopper::forms.group class="p-4 sm:p-5" label="shopper::layout.forms.label.brand" for="brand_id">
                            <x-shopper::forms.select wire:model.defer="selectedBrand" id="brand_id" x-data="{}" x-init="function () { choices($el) }">
                                <option value="0">{{ __('shopper::pages/brands.empty_brand') }}</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" @selected($brand->id === $brand_id)>{{ $brand->name }}</option>
                                @endforeach
                            </x-shopper::forms.select>
                        </x-shopper::forms.group>
                        <x-shopper::forms.group class="p-4 sm:p-5" label="shopper::layout.sidebar.collections" for="collection_ids">
                            <select
                                wire:model.defer="collection_ids"
                                id="collection_ids"
                                multiple
                                x-data="{}"
                                x-init="
                                  function() {
                                    tomSelect($el, {
                                        plugins: ['remove_button'],
			                            persist: false,
                                        maxItems: 3,
                                        allowEmptyOption: true,
                                        render: {
                                            option: function(data, escape) {
                                                return `<div class='text-sm leading-5 text-secondary-500 dark:text-secondary-400'>${escape(data.text)}</div>`;
                                            },
                                        }
                                    })
                                 }"
                            >
                                @foreach($collections as $collection)
                                    <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                                @endforeach
                            </select>
                        </x-shopper::forms.group>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow dark:bg-secondary-800">
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('shopper::pages/products.product_categories') }}</h4>
                    </div>
                    <div class="px-4 py-3">
                        <div class="p-2 space-y-3 overflow-scroll border rounded-md shadow-sm max-h-96 border-secondary-200 hide-scroll dark:border-secondary-700">
                            @foreach($categories as $category)
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
                                                <x-heroicon-o-plus-sm x-show="!display" class="w-5 h-5" />
                                                <x-heroicon-o-minus-sm x-show="display" class="w-5 h-5" />
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
                            @endforeach
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div class="pt-5 mt-6 border-t border-secondary-200 dark:border-secondary-700">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper::loader wire:loading wire:target="store" />
                {{ __('shopper::layout.forms.actions.update') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</div>
