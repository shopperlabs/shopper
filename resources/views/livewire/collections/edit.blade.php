@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

<div>
    <x:shopper-breadcrumb back="shopper.collections.index">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.collections.index')" title="Collections" />
    </x:shopper-breadcrumb>

    <x-shopper-heading class="mt-3">
        <x-slot name="title">
            {{ $name }}
        </x-slot>

        <x-slot name="action">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                {{ __('Update') }}
            </x-shopper-button>
        </x-slot>
    </x-shopper-heading>

    <div class="mt-6 grid sm:grid-cols-6 gap-4 lg:gap-6">
        <div class="sm:col-span-4 space-y-5">
            <div class="bg-white rounded-lg shadow p-4 sm:p-5 dark:bg-gray-800">
                <x-shopper-input.group label="Name" for="name" isRequired :error="$errors->first('name')">
                    <x-shopper-input.text wire:model="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Summers Collections, Christmas promotions...') }}" />
                </x-shopper-input.group>
                <div class="mt-5">
                    <x-shopper-input.group label="Description" for="description">
                        <x-shopper-input.rich-text wire:model.lazy="description" id="description" :initialValue="$description" />
                    </x-shopper-input.group>
                </div>
            </div>

            <livewire:shopper-collections.products :collection="$collection" />

            <div class="bg-white rounded-lg shadow-md divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('Search engine listing preview') }}</h3>
                        @if(! $updateSeo)
                            <button wire:click="updateSeo" type="button" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-blue-600 hover:text-blue-800 dark:text-blue-500">{{ __('Edit SEO preview') }}</button>
                        @endif
                    </div>
                    <div class="mt-4">
                        @if(! $updateSeo)
                            <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">{{ __('Add a title and description to see how this collection might appear in a search engine listing.') }}</p>
                        @else
                            <div class="flex flex-col">
                                <h3 class="text-base text-blue-800 font-medium leading-6 dark:text-blue-500">{{ $seoTitle }}</h3>
                                <span class="mt-1 text-green-600 text-sm leading-5 dark:text-green-500">{{ env('APP_URL') }}/collections/{{ str_slug($name) }}</span>
                                <p class="mt-1 text-gray-500 text-sm leading-5 dark:text-gray-400">{{ str_limit($seoDescription, 160) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @if($updateSeo)
                    <div class="px-4 py-5 sm:px-6 space-y-5">
                        <x-shopper-input.group for="seo_title" label="Title">
                            <x-shopper-input.text wire:model.debounce.500ms="seoTitle" id="seo_title" type="text" autocomplete="off" />
                        </x-shopper-input.group>
                        <div>
                            <div class="flex items-center justify-between">
                                <x-shopper-label for="seo_description" :value="__('Description')" />
                                <span class="ml-4 text-sm leading-5 text-gray-500 dark:text-gray-400">{{ __('160 characters') }}</span>
                            </div>
                            <div class="mt-1 rounded-md shadow-sm">
                                <x-shopper-input.textarea wire:model.debounce.500ms="seoDescription" id="seo_description" />
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
                    class="bg-white rounded-md shadow p-4 sm:p-5 dark:bg-gray-800"
                >
                    <x-shopper-label for="date" :value="__('Collection availability')" />
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon-o-calendar class="h-5 w-5 text-gray-400 dark:text-gray-500" />
                        </div>
                        <x-shopper-input.text wire:model.debounce.350ms="publishedAt" x-ref="input" id="date" type="text" class="pl-10" placeholder="{{ __('Choose a date') }}" readonly />
                    </div>
                    @if($publishedAt)
                        <div class="mt-2 flex items-start">
                            <div class="mt-1 flex-shrink-0 w-2.5 h-2.5 rounded-full bg-blue-600"></div>
                            <p class="ml-2.5 text-sm text-gray-500 leading-5 dark:text-gray-400">
                                {{ __('Will be published on:') }} <br>
                                {{ $publishedAtFormatted }}
                            </p>
                        </div>
                    @else
                        <p class="mt-2 text-sm leading-5 text-gray-500 dark:text-gray-400">
                            {{ __('Specify a publication date so that your collections are scheduled on your store.') }}
                        </p>
                    @endif
                </div>
                <div class="bg-white rounded-md shadow overflow-hidden p-4 sm:p-5 dark:bg-gray-800">
                    <h4 class="block text-sm font-medium leading-5 text-gray-700 dark:text-gray-300">{{ __('Image preview') }}</h4>
                    <div class="mt-1">
                        <x-shopper-input.single-upload id="file" wire:click="removeImage" wire:model="file" :file="$file" :error="$errors->first('file')" />
                    </div>
                    @if($media)
                        <div class="flex items-center justify-between mt-4 p-2 bg-gray-50 rounded-md border border-dashed border-gray-200 dark:bg-gray-700 dark:border-gray-700">
                            <div class="flex flex-1 items-center space-x-2 truncate">
                                <div class="flex-shrink-0 w-10 h-10 overflow-hidden rounded-md">
                                    <img class="h-full w-full object-cover" src="{{ $media->file_path }}" alt="">
                                </div>
                                <div class="truncate">
                                    <h4 class="text-sm leading-5 text-gray-500 truncate dark:text-gray-500">{{ $media->file_name }}</h4>
                                    <p class="text-xs leading-4 text-gray-400 dark:text-gray-500">{{ $media->file_size }}</p>
                                </div>
                            </div>
                            <button wire:click="deleteImage({{ $media->id }})" wire:loading.attr="disabled" type="button" class="ml-4 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                                <svg wire:loading wire:target="deleteImage" class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                                <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>

    <div class="mt-6 border-t border-gray-200 pt-5 pb-10 dark:border-gray-700">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                {{ __('Update') }}
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
