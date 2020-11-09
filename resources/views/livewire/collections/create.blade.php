@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

<div>
    <x:shopper-breadcrumb back="shopper.collections.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
        <a href="{{ route('shopper.collections.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Collections') }}</a>
    </x:shopper-breadcrumb>

    <div class="mt-3 pb-5 border-b border-gray-200 space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
        <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            {{ __("Create collection") }}
        </h3>
        <div class="flex">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                {{ __("Save") }}
            </x-shopper-button>
        </div>
    </div>

    <div class="mt-6 grid sm:grid-cols-6 gap-4 lg:gap-6">
        <div class="sm:col-span-4 space-y-5">
            <div class="bg-white rounded-lg shadow p-4 sm:p-5">
                <div>
                    <x-shopper-input.group label="Name" for="name" isRequired :error="$errors->first('name')">
                        <input wire:model="name" id="name" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" placeholder="Summers Collections, Christmas promotions...">
                    </x-shopper-input.group>
                </div>
                <div class="mt-5">
                    <x-shopper-input.group label="Description" for="description">
                        <x-shopper-input.rich-text wire:model.lazy="description" id="description" />
                    </x-shopper-input.group>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow overflow-hidden pt-4 sm:pt-5">
                <h3 class="text-base text-gray-900 leading-6 px-4 sm:px-5 font-medium">{{ __("Collection type") }}</h3>
                <div class="p-4 sm:p-5" x-data="radioGroup()">
                    <div class="bg-white rounded-md grid gap-4 sm:grid-cols-2 sm:gap-6" x-ref="radiogroup">

                        <div :class="{ 'border-gray-200': !(active === 0), 'bg-blue-50 border-blue-200 z-10': active === 0 }" class="relative border rounded-md p-4 flex bg-blue-50 border-blue-200 z-10">
                            <div class="flex items-center h-5">
                                <input wire:model="type" id="collection-type-0" name="type" value="manual" type="radio" @click="select(0)" @keydown.space="select(0)" @keydown.arrow-up="onArrowUp(0)" @keydown.arrow-down="onArrowDown(0)" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out cursor-pointer" checked="">
                            </div>
                            <label for="collection-type-0" class="ml-3 flex flex-col cursor-pointer">
                                <span :class="{ 'text-blue-900': active === 0, 'text-gray-900': !(active === 0) }" class="block text-sm leading-5 font-medium text-blue-900">
                                    {{ __("Manual") }}
                                </span>
                                <span :class="{ 'text-blue-700': active === 0, 'text-gray-500': !(active === 0) }" class="mt-0.5 block text-xs leading-4 text-blue-700">
                                    {{ __("Add the products to this collection one by one.") }}
                                </span>
                            </label>
                        </div>

                        <div :class="{ 'border-gray-200': !(active === 1), 'bg-blue-50 border-blue-200 z-10': active === 1 }" class="relative border rounded-md border-gray-200 p-4 flex">
                            <div class="flex items-center h-5">
                                <input wire:model="type" id="collection-type-1" name="type" value="automatic" type="radio" @click="select(1)" @keydown.space="select(1)" @keydown.arrow-up="onArrowUp(1)" @keydown.arrow-down="onArrowDown(1)" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out cursor-pointer">
                            </div>
                            <label for="collection-type-1" class="ml-3 flex flex-col cursor-pointer">
                                <span :class="{ 'text-blue-900': active === 1, 'text-gray-900': !(active === 1) }" class="block text-sm leading-5 font-medium text-gray-900">
                                    {{ __("Automated") }}
                                </span>
                                <span :class="{ 'text-blue-700': active === 1, 'text-gray-500': !(active === 1) }" class="mt-0.5 block text-xs leading-4 text-gray-500">
                                    {{ __("Products that match the conditions you set will automatically be added to collection") }}
                                </span>
                            </label>
                        </div>

                    </div>
                </div>
                @if($type === 'automatic')
                    <div class="border-t border-gray-200 p-4 sm:p-5 space-y-4">
                        <h3 class="text-base text-gray-900 leading-6 font-medium">{{ __("Conditions") }}</h3>
                        <div class="flex items-center space-x-6">
                            <p class="text-sm leading-5 text-gray-500">{{ __("Products must match:") }}</p>
                            <div class="flex items-center">
                                <input wire:model="condition_match" id="all" value="all" type="radio" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                <label for="all" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-gray-700">{{ __("All conditions") }}</span>
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input wire:model="condition_match" id="any" value="any" type="radio" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                <label for="any" class="ml-3 cursor-pointer">
                                    <span class="block text-sm leading-5 font-medium text-gray-700">{{ __("Any condition") }}</span>
                                </label>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="relative">
                                <span class="inline-flex rounded-md shadow-sm">
                                    <button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ __("Add another condition") }}
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="bg-white rounded-lg shadow-md divide-y divide-gray-200">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ __("Search engine listing preview") }}</h3>
                        @if(! $updateSeo)
                            <button wire:click="updateSeo" type="button" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out">{{ __("Edit SEO preview") }}</button>
                        @endif
                    </div>
                    <div class="mt-4">
                        @if(! $updateSeo)
                            <p class="text-sm leading-5 text-gray-500">{{ __("Add a title and description to see how this collection might appear in a search engine listing.") }}</p>
                        @else
                            <div class="flex flex-col">
                                <h3 class="text-base text-blue-800 font-medium leading-6">{{ $seoTitle }}</h3>
                                <span class="mt-1 text-green-600 text-sm leading-5">{{ env('APP_URL') }}/collections/{{ str_slug($name) }}</span>
                                <p class="mt-1 text-gray-500 text-sm leading-5">{{ str_limit($seoDescription, 160) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @if($updateSeo)
                    <div class="px-4 py-5 sm:px-6 space-y-5">
                        <div>
                            <label for="seo_title" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Title") }}</label>
                            <div class="mt-1 rounded-md shadow-sm">
                                <input
                                    wire:model="seoTitle"
                                    id="seo_title"
                                    type="text"
                                    class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                    autocomplete="off"
                                >
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center">
                                <label for="seo_description" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Description") }}</label>
                                <span class="ml-4 text-sm leading-5 text-gray-500">{{ __("160 characters") }}</span>
                            </div>
                            <div class="mt-1 rounded-md shadow-sm">
                                <textarea
                                    wire:model="seoDescription"
                                    id="seo_description"
                                    rows="4"
                                    class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                >
                                </textarea>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="sm:col-span-2">
            <aside class="sticky top-6 space-y-5">
                <div
                    x-data
                    x-init="flatpickr($refs.input, {dateFormat: 'Y-m-d'});"
                    class="bg-white rounded-md shadow p-4 sm:p-5"
                >
                    <label for="date" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Collection availability") }}</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <input wire:model="publishedAt" x-ref="input" id="date" type="date" class="form-input block w-full pl-10 sm:text-sm sm:leading-5" placeholder="{{ __("Choose a date") }}" readonly />
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
                            {{ __("Specify a publication date so that your collections are scheduled on your store.") }}
                        </p>
                    @endif
                </div>
                <div class="bg-white rounded-md shadow overflow-hidden p-4 sm:p-5">
                    <h4 class="block text-sm font-medium leading-5 text-gray-700">{{ __("Image preview") }}</h4>
                    <div class="mt-1">
                        @if($file)
                            <div>
                                <div class="flex-shrink-0 rounded-md overflow-hidden">
                                    <img class="h-40 w-full object-cover rounded-md" src="{{ $file->temporaryUrl() }}" alt="">
                                    <div class="flex items-center mt-2">
                                        <button wire:click="removeImage" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                                            <svg class="h-5 w-5 mr-1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            {{ __("Remove") }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="w-full">
                                <label for="file" class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md cursor-pointer">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <p class="mt-1 text-sm text-gray-600">
                                            <span class="font-medium text-blue-600 hover:text-blue-500 focus:outline-none focus:underline transition duration-150 ease-in-out">
                                                {{ __("Upload a file") }}
                                            </span>
                                            {{ __("or drag and drop") }}
                                        </p>
                                        <p class="mt-1 text-xs text-gray-500">
                                            PNG, JPG, GIF up to 1MB
                                        </p>
                                        <input id="file" type="file" wire:model="file" class="sr-only">
                                    </div>
                                </label>
                                @error('file')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
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
                {{ __("Save") }}
            </x-shopper-button>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        function radioGroup() {
            return {
                active: {{ $type === 'manual' ? 0 : 1 }},
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
