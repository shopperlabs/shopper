<div x-data="{ on: @entangle('is_enabled') }">
    <x:shopper-breadcrumb back="shopper.categories.index">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
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
            <div class=" p-4 sm:p-5 bg-white rounded-lg shadow dark:bg-secondary-800">
                <div>
                    <x-shopper-forms.group label="Name" for="name" isRequired :error="$errors->first('name')">
                        <x-shopper-forms.input wire:model.defer="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Women Shoes, Baby Clothes clothes') }}" />
                    </x-shopper-forms.group>
                </div>
                <div class="mt-4">
                    <x-shopper-forms.group label="Parent" for="category" wire:ignore>
                        <x-shopper-forms.select wire:model.defer="selectedCategory" id="category" x-data="{}" x-init="function () { choices($el) }">
                            <option value="0">{{ __('--- No category ---') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @if($category->id === $parent_id) selected @endif>
                                    {{ $category->name }}
                                </option>

                                @foreach($category->children as $child)
                                    @include('shopper::livewire.categories.resources.views.components.forms.option-category', ['name' => $category->name, 'category' => $child])
                                @endforeach
                            @endforeach
                        </x-shopper-forms.select>
                    </x-shopper-forms.group>
                </div>
                <div class="mt-5 py-4 border-t border-b border-secondary-200 dark:border-secondary-700">
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <span wire:model="is_enabled" role="checkbox" tabindex="0" x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-secondary-200 dark:bg-secondary-700': !on, 'bg-primary-600': on }" class="bg-secondary-200 relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-brand">
                                <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"></span>
                            </span>
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <x-shopper-label for="online" :value="__('Visibility')" />
                            <p class="text-sm text-secondary-500 dark:text-secondary-400">{{ __('Set category visibility for the customers.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <x-shopper-forms.group label="Description" for="description">
                        <livewire:shopper-forms.trix :value="$description" />
                    </x-shopper-forms.group>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md divide-y divide-secondary-200 dark:bg-secondary-800 dark:divide-secondary-700">
                <div class="p-4 sm:p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">{{ __('Search engine listing preview') }}</h3>
                        @if(! $updateSeo)
                            <button wire:click="updateSeo" type="button" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-primary-600 hover:text-primary-800 transition duration-150 ease-in-out dark:text-primary-500/50">{{ __('Edit SEO preview') }}</button>
                        @endif
                    </div>
                    <div class="mt-4">
                        @if(! $updateSeo)
                            <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ __('Add a title and description to see how this collection might appear in a search engine listing.') }}</p>
                        @else
                            <div class="flex flex-col">
                                <h3 class="text-base text-primary-800 font-medium leading-6 dark:text-primary-500/50">{{ $seoTitle }}</h3>
                                <span class="mt-1 text-primary-600 text-sm leading-5 dark:text-primary-500/50">{{ env('APP_URL') }}/categories/{{ str_slug($name) }}</span>
                                <p class="mt-1 text-secondary-500 text-sm leading-5 dark:text-secondary-400">{{ str_limit($seoDescription, 160) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                @if($updateSeo)
                    <div class="px-4 py-5 sm:px-6 space-y-5">
                        <x-shopper-forms.group for="seo_title" label="Title">
                            <x-shopper-forms.input wire:model.debounce.500ms="seoTitle" id="seo_title" type="text" autocomplete="off" />
                        </x-shopper-forms.group>
                        <div>
                            <div class="flex items-center justify-between">
                                <x-shopper-label for="seo_description" :value="__('Description')" />
                                <span class="ml-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ __('160 characters') }}</span>
                            </div>
                            <div class="mt-1 rounded-md shadow-sm">
                                <x-shopper-forms.textarea wire:model.debounce.500ms="seoDescription" id="seo_description" />
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="sm:col-span-2">
            <aside class="sticky top-6 space-y-5">
                <div class="bg-white rounded-md shadow overflow-hidden dark:bg-secondary-800">
                    <div class="p-4 sm:p-5">
                        <x-shopper-label :value="__('Image preview')" />
                        <div class="mt-1">
                            <livewire:shopper-forms.uploads.single />
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

</div>
