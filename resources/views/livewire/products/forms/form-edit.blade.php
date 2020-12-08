@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

<div>
    <div class="mt-6 grid sm:grid-cols-6 gap-4 lg:gap-6">
        <div class="sm:col-span-4 space-y-5">
            <div class="bg-white rounded-lg shadow p-4 sm:p-5">
                <div>
                    <x-shopper-input.group label="Name" for="name" isRequired :error="$errors->first('name')">
                        <input wire:model="name" id="name" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" placeholder="Apple, Nike, Samsung...">
                    </x-shopper-input.group>
                </div>
                <div class="mt-5 border-t border-gray-200 pt-4">
                    <x-shopper-input.group label="Description" for="description">
                        <x-shopper-input.rich-text wire:model.lazy="description" id="description" :initialValue="$description" />
                    </x-shopper-input.group>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4 sm:p-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-gray-900">{{ __("Product Media") }}</h4>
                <div class="mt-4">
                    <x-shopper-input.drag-upload id="file" wire:click="removeImage" wire:model="file" :file="$file" :error="$errors->first('file')" />
                </div>
            </div>
            <div class="bg-white rounded-lg shadow pt-4 sm:pt-5 overflow-hidden">
                <h4 class="block text-base font-medium leading-6 text-gray-900 px-4 sm:px-5">{{ __("Pricing") }}</h4>
                <div class="divide-y divide-gray-200">
                    <div class="grid gap-4 sm:grid-cols-6 sm:gap-6 p-4 sm:p-5">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="price_amount" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Price amount") }}</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input wire:model="price_amount" id="price_amount" type="number" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" placeholder="0.00" min="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="old_price_amount" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Compare at price") }}</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input wire:model="old_price_amount" id="old_price_amount" type="number" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" placeholder="0.00" min="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-6 gap-6 p-4 sm:p-5">
                        <div class="col-span-6 sm:col-span-3">
                            <label for="cost_amount" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Cost per item") }}</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input wire:model="cost_amount" id="cost_amount" type="number" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" placeholder="0.00" min="0">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm sm:leading-5">{{ $currency }}</span>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">{{ __("Customers won’t see this.") }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div x-data="radioGroup()" class="bg-white rounded-lg shadow overflow-hidden p-4 sm:p-5">
                <h4 class="block text-base font-medium leading-6 text-gray-900">{{ __("Product type") }}</h4>
                <div x-ref="radiogroup" class="mt-5 grid gap-4 sm:grid-cols-2 sm:gap-5">
                    <div :class="{ 'border-gray-200': !(active === 0), 'bg-blue-50 border-blue-200 z-10': active === 0 }" class="sm:col-span-1 relative border rounded-md p-4 flex bg-blue-50 border-blue-200 z-10">
                        <div class="flex items-center h-5">
                            <input wire:model="type" id="product-type-0" name="type" value="deliverable" type="radio" @click="select(0)" @keydown.space="select(0)" @keydown.arrow-up="onArrowUp(0)" @keydown.arrow-down="onArrowDown(0)" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out cursor-pointer" checked="">
                        </div>
                        <label for="product-type-0" class="ml-3 flex flex-col cursor-pointer">
                            <span :class="{ 'text-blue-900': active === 0, 'text-gray-900': !(active === 0) }" class="block text-sm leading-5 font-medium text-blue-900">
                                {{ __("Deliverable") }}
                            </span>
                            <span :class="{ 'text-blue-700': active === 0, 'text-gray-500': !(active === 0) }" class="mt-0.5 block text-xs leading-4 text-blue-700">
                                {{ __("This product can be delivered to a customer.") }}
                            </span>
                        </label>
                    </div>

                    <div :class="{ 'border-gray-200': !(active === 1), 'bg-blue-50 border-blue-200 z-10': active === 1 }" class="sm:col-span-1 relative border rounded-md border-gray-200 p-4 flex">
                        <div class="flex items-center h-5">
                            <input wire:model="type" id="product-type-1" name="type" value="downloadable" type="radio" @click="select(1)" @keydown.space="select(1)" @keydown.arrow-up="onArrowUp(1)" @keydown.arrow-down="onArrowDown(1)" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out cursor-pointer">
                        </div>
                        <label for="product-type-1" class="ml-3 flex flex-col cursor-pointer">
                            <span :class="{ 'text-blue-900': active === 1, 'text-gray-900': !(active === 1) }" class="block text-sm leading-5 font-medium text-gray-900">
                                {{ __("Downloadable") }}
                            </span>
                            <span :class="{ 'text-blue-700': active === 1, 'text-gray-500': !(active === 1) }" class="mt-0.5 block text-xs leading-4 text-gray-500">
                                {{ __("This product can be downloaded by a customer.") }}
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="sm:col-span-2">
            <aside class="space-y-5">
                <div class="bg-white rounded-lg shadow overflow-hidden divide-y divide-gray-200">
                    <div class="p-4 sm:p-5">
                        <h4 class="text-sm leading-5 text-gray-700 font-medium">{{ __("Product status") }}</h4>
                        <div class="mt-4 px-3 py-2.5 bg-blue-50 rounded-md text-blue-600 flex items-center justify-between">
                            <div class="flex items-center">
                                <span class="h-8 w-8 flex items-center justify-center rounded-md bg-blue-600 flex-shrink-0">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </span>
                                <span class="font-semibold ml-3 text-sm">{{ __("Visible") }}</span>
                            </div>
                            <div>
                                <span wire:model="isVisible" x-data="{ on: @if($isVisible) true @else false @endif }" role="checkbox" tabindex="0"x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-gray-100': !on, 'bg-blue-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-blue bg-gray-200">
                                    <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                    <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200 translate-x-0"></span>
                                </span>
                            </div>
                        </div>
                        <p class="mt-2 text-gray-500 leading-5 text-sm">
                            {{ __("This product will be hidden from all sales channels.") }}
                        </p>
                    </div>
                    <div
                        x-data
                        x-init="flatpickr($refs.input, {dateFormat: 'Y-m-d'});"
                        class="p-4 sm:p-5"
                    >
                        <label for="date" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Product availability") }}</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input wire:model="publishedAt" x-ref="input" id="date" type="text" class="form-input block w-full pl-10 sm:text-sm sm:leading-5" placeholder="{{ __("Choose a date") }}" readonly />
                        </div>
                        @if($publishedAt)
                            <div class="mt-2 flex items-start">
                                <div class="mt-1 flex-shrink-0 w-2.5 h-2.5 rounded-full bg-blue-600"></div>
                                <p class="ml-2.5 text-sm text-gray-500 leading-5">
                                    {{ __("Will be published on:") }} <br>
                                    {{ $publishedAtFormatted }}
                                </p>
                            </div>
                        @else
                            <p class="mt-2 text-sm leading-5 text-gray-500">
                                {{ __("Specify a publication date so that your product are scheduled on your store.") }}
                            </p>
                        @endif
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow">
                    <div class="px-4 pt-4 sm:px-5 sm:pt-5">
                        <h4 class="block text-base font-medium leading-6 text-gray-900">{{ __("Product associations") }}</h4>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="p-4 sm:p-5">
                            <label for="brand_id" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Brand") }}</label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <select wire:model="brand_id" id="brand_id" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" @if($loop->first) selected @endif>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="p-4 sm:p-5">
                            <label for="categories" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Categories") }}</label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <select wire:model="category_ids" id="categories" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" multiple>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="p-4 sm:p-5">
                            <label for="collections" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Collections") }}</label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <select wire:model="collection_ids" id="collections" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" multiple>
                                    @foreach($collections as $collection)
                                        <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div class="mt-6 border-t border-gray-200 pt-5">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                {{ __("Update") }}
            </x-shopper-button>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        function radioGroup() {
            return {
                active: {{ $type === 'deliverable' ? 0 : 1 }},
                onArrowUp(index) {
                    this.select(this.active - 1 < 0 ? this.$refs.radiogroup.children.length - 1 : this.active - 1);
                },
                onArrowDown(index) {
                    this.select(this.active + 1 > this.$refs.radiogroup.children.length - 1 ? 0 : this.active + 1);
                },
                select(index) {
                    this.active = index;
                },
            };
        }
    </script>
@endpush
