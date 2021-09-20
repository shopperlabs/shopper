<div x-data="{ on: @entangle('is_enabled') }">
    <x:shopper-breadcrumb back="shopper.categories.index">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.categories.index')" title="Categories" />
    </x:shopper-breadcrumb>

    <x-shopper-heading class="mt-3">
        <x-slot name="title">
            {{ __('Create category') }}
        </x-slot>

        <x-slot name="action">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                {{ __('Save') }}
            </x-shopper-button>
        </x-slot>
    </x-shopper-heading>

    <div class="mt-6 grid sm:grid-cols-6 gap-4 sm:gap-6">
        <div class="sm:col-span-4 space-y-5">
            <div class=" p-4 sm:p-5 bg-white rounded-lg shadow dark:bg-gray-800">
                <div>
                    <x-shopper-input.group label="Name" for="name" isRequired :error="$errors->first('name')">
                        <x-shopper-input.text wire:model="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Women Shoes, Baby Clothes clothes') }}" />
                    </x-shopper-input.group>
                </div>
                <div class="mt-4">
                    <x-shopper-input.group label="Parent" for="parentId">
                        <x-shopper-input.select wire:model="parent_id" id="parentId">
                            <option>{{ __('Select parent category') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </x-shopper-input.select>
                    </x-shopper-input.group>
                </div>
                <div class="mt-5 py-4 border-t border-b border-gray-200 dark:border-gray-700">
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <span wire:model="is_enabled" role="checkbox" tabindex="0" x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': !on, 'bg-blue-600': on }" class="bg-gray-200 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-brand">
                                <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"></span>
                            </span>
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <x-shopper-label for="online" :value="__('Visibility')" />
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Set category visibility for the customers.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <x-shopper-input.group label="Description" for="description">
                        <livewire:shopper-trix :value="$description" />
                    </x-shopper-input.group>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">{{ __('Search engine listing preview') }}</h3>
                        @if(! $updateSeo)
                            <button wire:click="updateSeo" type="button" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out dark:text-blue-500">{{ __('Edit SEO preview') }}</button>
                        @endif
                    </div>
                    <div class="mt-4">
                        @if(! $updateSeo)
                            <p class="text-sm leading-5 text-gray-500 dark:text-gray-400">{{ __('Add a title and description to see how this collection might appear in a search engine listing.') }}</p>
                        @else
                            <div class="flex flex-col">
                                <h3 class="text-base text-blue-800 font-medium leading-6 dark:text-blue-500">{{ $seoTitle }}</h3>
                                <span class="mt-1 text-green-600 text-sm leading-5 dark:text-green-500">{{ env('APP_URL') }}/categories/{{ str_slug($name) }}</span>
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
                <div class="bg-white rounded-md shadow overflow-hidden dark:bg-gray-800">
                    <div class="p-4 sm:p-5">
                        <x-shopper-label :value="__('Image preview')" />
                        <div class="mt-1">
                            <x-shopper-input.single-upload id="file" wire:click="removeSingleMediaPlaceholder" wire:model="file" :file="$file" :error="$errors->first('file')" />
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

</div>
