<div>
    <div class="mt-6 grid xl:grid-cols-6 gap-4 lg:gap-6">
        <div class="xl:col-span-4 space-y-5">
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow p-4 sm:p-5">
                <div>
                    <x-shopper::forms.group label="Name" for="name" isRequired :error="$errors->first('name')">
                        <x-shopper::forms.input wire:model.defer="name" id="name" type="text" autocomplete="off" placeholder="Apple, Nike, Samsung..." />
                    </x-shopper::forms.group>
                </div>
                <div class="mt-5 border-t border-secondary-200 dark:border-secondary-700 pt-4">
                    <x-shopper::forms.group label="Description" for="description">
                        <livewire:shopper-forms.trix :value="$description" />
                    </x-shopper::forms.group>
                </div>
            </div>
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow p-4 sm:p-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('Product Media') }}</h4>
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
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow pt-4 sm:pt-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white px-4 sm:px-5">{{ __('Pricing') }}</h4>
                <div class="divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="grid gap-4 sm:grid-cols-6 sm:gap-6 p-4 sm:p-5">
                        <div class="col-span-6 sm:col-span-3">
                            <x-inputs.currency
                                :label="__('Price amount')"
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
                                :label="__('Compare at price')"
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
                                :label="__('Cost per item')"
                                placeholder="0.00"
                                wire:model.defer="cost_amount"
                                class="dark:bg-secondary-700 dark:border-transparent dark:focus:border-primary-500 dark:text-white"
                                min="0"
                                thousands=","
                                decimal="."
                                :suffix="$currency"
                                :hint="__('Customers won’t see this.')"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="xl:col-span-2">
            <aside class="space-y-5">
                <div class="bg-white dark:bg-secondary-800 rounded-lg shadow divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="p-4 sm:p-5">
                        <x-shopper::label value="{{ __('Product status') }}" />
                        <div class="mt-4 px-3 py-2.5 bg-primary-500 bg-opacity-10 rounded-md text-primary-600 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="h-8 w-8 flex items-center justify-center rounded-md bg-primary-600 shrink-0">
                                    <x-heroicon-o-eye class="h-5 w-5 text-white" />
                                </span>
                                <span class="font-semibold ml-3 text-sm">{{ __('Visible') }}</span>
                            </div>
                            <div>
                                <span wire:model="isVisible" x-data="{ on: @entangle('isVisible') }" role="checkbox" tabindex="0" @click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" :class="{ 'bg-secondary-200 dark:bg-secondary-600': !on, 'bg-primary-600': on }" class="relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-primary bg-secondary-200">
                                    <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                    <span aria-hidden="true" :class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200 translate-x-0"></span>
                                </span>
                            </div>
                        </div>
                        <p class="mt-2 text-secondary-500 dark:text-secondary-400 leading-5 text-sm">
                            {{ __("This product will be hidden from all sales channels.") }}
                        </p>
                    </div>
                    <div class="p-4 sm:p-5">
                        <x-datetime-picker
                            :label="__('Product availability')"
                            :placeholder="__('Pick a date')"
                            wire:model.lazy="publishedAt"
                            parse-format="YYYY-MM-DD HH:mm"
                            time-format="24"
                            without-timezone
                            class="dark:bg-secondary-700"
                        />
                        @if($publishedAt)
                            <div class="mt-2 flex items-start">
                                <div class="mt-1 shrink-0 w-2.5 h-2.5 rounded-full bg-primary-600"></div>
                                <p class="ml-2.5 text-sm text-secondary-500 dark:text-secondary-400 leading-5">
                                    {{ __('Will be published on:') }} <br>
                                    {{ $publishedAtFormatted }}
                                </p>
                            </div>
                        @else
                            <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                {{ __('Specify a publication date so that your product are scheduled on your store.') }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="bg-white dark:bg-secondary-800 rounded-lg shadow">
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('Product associations') }}</h4>
                    </div>
                    <div class="divide-y divide-secondary-200 dark:divide-secondary-700">
                        <x-shopper::forms.group class="p-4 sm:p-5" label="Brand" for="brand_id">
                            <x-shopper::forms.select wire:model.defer="brand_id" id="brand_id">
                                <option value="0">{{ __('No brand') }}</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </x-shopper::forms.select>
                        </x-shopper::forms.group>
                        <div class="p-4 sm:p-5">
                            <x-select
                                :label="__('Collections')"
                                placeholder="Select collections"
                                wire:model.defer="collection_ids"
                                class="dark:bg-secondary-800"
                                multiselect
                            >
                                @foreach($collections as $collection)
                                    <x-select.user-option
                                        :img="$collection->getFirstMediaUrl(config('shopper.system.storage.disks.uploads'))"
                                        :label="$collection->name"
                                        :value="$collection->id"
                                    />
                                @endforeach
                            </x-select>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-secondary-800 rounded-lg shadow">
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-secondary-900 dark:text-white">{{ __('Product categories') }}</h4>
                    </div>
                    <div class="px-4 py-3">
                        <div class="space-y-3 p-2 max-h-96 border border-secondary-200 rounded-md shadow-sm overflow-scroll hide-scroll dark:border-secondary-700">
                            @foreach($categories as $category)
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <x-shopper::forms.checkbox id="category-{{ $category->id }}" wire:model.defer="category_ids" value="{{ $category->id }}" />
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="category-{{ $category->id }}" class="font-medium text-secondary-700 dark:text-secondary-300">{{ $category->name }}</label>
                                    </div>
                                </div>

                                @if($category->children->isNotEmpty())
                                    <div class="ml-4 space-y-3">
                                        @foreach($category->children as $child)
                                            @include('shopper::components.forms.checkbox-category', ['parent' => $category->parent_id, 'category' => $child])
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div class="mt-6 border-t border-secondary-200 dark:border-secondary-700 pt-5">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper::loader wire:loading wire:target="store" />
                {{ __('Update') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</div>
