<x-shopper::container>
    <div class="grid gap-x-4 gap-y-5 mt-6 xl:grid-cols-6 lg:gap-y-6 lg:gap-x-12">
        <div class="space-y-8 xl:col-span-4">
            <div>
                <div>
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.name')" for="name" isRequired :error="$errors->first('name')">
                        <x-shopper::forms.input wire:model.defer="name" id="name" type="text" class="dark:bg-gray-800 dark:border-gray-800" autocomplete="off" placeholder="Apple, Nike, Samsung..." />
                    </x-shopper::forms.group>
                </div>
                <div class="pt-4 mt-5 border-t border-gray-200 dark:border-gray-700">
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.description')" for="description">
                        <livewire:shopper-forms.trix :value="$description" />
                    </x-shopper::forms.group>
                </div>
            </div>
            <div>
                <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white">
                    {{ __('shopper::words.media') }}
                </h4>
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
                        <p class="mt-2 text-sm text-danger-500">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
            <div class="relative">
                <div class="flex items-center justify-between">
                    <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white">
                        {{ __('shopper::words.pricing') }}
                    </h4>
                    <div x-data="{ display: false }">
                        <button @click="display = true" x-tooltip.raw="{{ __('shopper::pages/products.about_pricing') }}" type="button" class="inline-flex text-sm text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300">
                            <x-untitledui-help-circle class="h-5 w-5" />
                        </button>
                        <div x-cloak x-show="display" @click.outside="display = false" class="absolute z-30 top-0 inset-x-0 p-4 mr-4 rounded-lg bg-gray-50 border border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                            <div class="flex">
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('shopper::pages/products.about_pricing_content') }}
                                    </p>
                                </div>
                                <div class="ml-auto pl-3">
                                    <div class="-mx-1.5 -my-1.5">
                                        <button @click="display = false" type="button" class="inline-flex bg-gray-50 dark:bg-gray-700 rounded-md p-1 text-gray-500 dark:text-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-primary-50 focus:ring-primary-600">
                                            <span class="sr-only">{{ __('words::words.dismiss') }}</span>
                                            <x-untitledui-x class="h-5 w-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="grid gap-4 sm:grid-cols-6 sm:gap-6 py-5">
                        <div class="col-span-6 sm:col-span-3">
                            <x-inputs.currency
                                :label="__('shopper::layout.forms.label.price_amount')"
                                placeholder="0.00"
                                wire:model.defer="price_amount"
                                class="dark:bg-gray-700 dark:border-transparent dark:focus:border-primary-500 dark:text-white"
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
                                class="dark:bg-gray-700 dark:border-transparent dark:focus:border-primary-500 dark:text-white"
                                min="0"
                                thousands=","
                                decimal="."
                                :suffix="$currency"
                            />
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 py-5">
                        <div class="col-span-6 sm:col-span-3">
                            <x-inputs.currency
                                :label="__('shopper::layout.forms.label.cost_per_item')"
                                placeholder="0.00"
                                wire:model.defer="cost_amount"
                                class="dark:bg-gray-700 dark:border-transparent dark:focus:border-primary-500 dark:text-white"
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
                <x-shopper::card class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="p-4 sm:p-5">
                        <x-shopper::label value="{{ __('shopper::pages/products.status') }}" />
                        <div class="mt-4 px-3 py-2.5 bg-primary-500 bg-opacity-10 rounded-md text-primary-600 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="flex items-center justify-center w-8 h-8 rounded-md bg-primary-600 shrink-0">
                                    <x-untitledui-eye class="w-5 h-5 text-white" />
                                </span>
                                <span class="ml-3 text-sm font-semibold">
                                    {{ __('shopper::layout.forms.label.visible') }}
                                </span>
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
                                      :class="{ 'bg-gray-200 dark:bg-gray-600': !on, 'bg-primary-600': on }"
                                      class="relative inline-flex h-6 transition-colors duration-200 ease-in-out border-2 border-transparent rounded-full cursor-pointer shrink-0 w-11 focus:outline-none focus:shadow-outline-primary bg-gray-200">
                                    <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                    <span aria-hidden="true"
                                          :class="{ 'translate-x-5': on, 'translate-x-0': !on }"
                                          class="inline-block w-5 h-5 transition duration-200 ease-in-out transform translate-x-0 bg-white rounded-full shadow"
                                    ></span>
                                </span>
                            </div>
                        </div>
                        <p class="mt-2 text-sm leading-5 text-gray-500 dark:text-gray-400">
                            {{ __('shopper::pages/products.visible_help_text') }}
                        </p>
                    </div>
                    <div class="p-4 sm:p-5">
                        <x-datetime-picker
                            :label="__('shopper::layout.forms.label.availability')"
                            :placeholder="__('shopper::layout.forms.placeholder.pick_a_date')"
                            wire:model.lazy="publishedAt"
                            parse-format="YYYY-MM-DD HH:mm"
                            display-format="{{ config('shopper.admin.date_time_format') }}"
                            time-format="24"
                            class="dark:bg-gray-700"
                        />
                        @if($publishedAt)
                            <div class="flex items-start mt-2">
                                <div class="mt-1 shrink-0 w-2.5 h-2.5 rounded-full bg-primary-600"></div>
                                <p class="ml-2.5 text-sm text-gray-500 dark:text-gray-400 leading-5">
                                    {{ __('shopper::words.published_on') }} <br>
                                    {{ $publishedAtFormatted }}
                                </p>
                            </div>
                        @else
                            <p class="mt-2 text-sm leading-5 text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/products.availability_description') }}
                            </p>
                        @endif
                    </div>
                </x-shopper::card>
                <x-shopper::card>
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white">
                            {{ __('shopper::pages/products.product_associations') }}
                        </h4>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700" wire:ignore>
                        <x-shopper::forms.group class="p-4 sm:p-5" :label="__('shopper::layout.forms.label.brand')" for="brand_id">
                            <x-shopper::forms.select wire:model.defer="selectedBrand" id="brand_id" x-data="{}" x-init="function () { choices($el) }">
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" @selected($brand->id === $brand_id)>{{ $brand->name }}</option>
                                @endforeach
                            </x-shopper::forms.select>
                        </x-shopper::forms.group>
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
                <x-shopper::card>
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-gray-900 dark:text-white">
                            {{ __('shopper::pages/products.product_categories') }}
                        </h4>
                    </div>
                    <div class="px-4 py-3">
                        <div class="p-2 space-y-3 overflow-scroll border rounded-md shadow-sm max-h-96 border-gray-200 hide-scroll dark:border-gray-700">
                            @forelse($categories as $category)
                                <div @if($category->children->isNotEmpty()) x-data="{ display: true }" @endif class="space-y-3">
                                    <div class="relative flex justify-between">
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <x-shopper::forms.checkbox id="category-{{ $category->id }}" wire:model.defer="category_ids" value="{{ $category->id }}" />
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="category-{{ $category->id }}" class="font-medium text-gray-700 dark:text-gray-300">{{ $category->name }}</label>
                                            </div>
                                        </div>
                                        @if($category->children->isNotEmpty())
                                            <button type="button" @click="display = !display" class="text-gray-500 dark:text-gray-400" aria-expanded="true" x-bind:aria-expanded="display.toString()">
                                                <x-untitledui-plus x-show="!display" class="h-5 w-5" />
                                                <x-untitledui-minus x-show="display" class="h-5 w-5" />
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
                                    <x-untitledui-tag-03 class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" />
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ __('shopper::pages/products.no_category') }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('shopper::pages/products.no_category_text') }}
                                    </p>
                                    <div class="mt-6">
                                        <x-shopper::buttons.primary :link="route('shopper.categories.create')">
                                            <x-untitledui-plus class="-ml-1 mr-2 h-5 w-5" />
                                            {{ __('shopper::pages/products.new_category') }}
                                        </x-shopper::buttons.primary>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </x-shopper::card>
            </aside>
        </div>
    </div>

    <div class="pt-5 mt-6 border-t border-gray-200 dark:border-gray-700">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper::loader wire:loading wire:target="store" />
                {{ __('shopper::layout.forms.actions.update') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</x-shopper::container>
