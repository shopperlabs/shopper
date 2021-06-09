<div x-data="{ on: @entangle('is_enabled') }">
    <x:shopper-breadcrumb back="shopper.categories.index">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.categories.index')" title="Categories" />
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
        <div class="sm:col-span-4">
            <div class=" p-4 sm:p-5bg-white rounded-lg shadow dark:bg-gray-800">
                <div>
                    <x-shopper-input.group label="Name" for="name" isRequired :error="$errors->first('name')">
                        <x-shopper-input.text wire:model="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Women Shoes, Baby Clothes clothes') }}" />
                    </x-shopper-input.group>
                </div>
                <div class="mt-4">
                    <x-shopper-input.group label="Parent" for="parentId">
                        <x-shopper-input.select wire:model="parent_id" id="parentId">
                            <option>{{ __("Select parent category") }}</option>
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
                        <x-shopper-input.rich-text wire:model.lazy="description" id="description" />
                    </x-shopper-input.group>
                </div>
            </div>
        </div>
        <div class="sm:col-span-2">
            <aside class="sticky top-6 space-y-5">
                <div class="bg-white rounded-md shadow overflow-hidden divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                    <div class="p-4 sm:p-5">
                        <x-shopper-input.group label="Slug (url)" for="slug" isRequired :error="$errors->first('slug')">
                            <x-shopper-input.text wire:model="slug" id="slug" type="text" autocomplete="off" />
                        </x-shopper-input.group>
                    </div>
                    <div class="p-4 sm:p-5">
                        <x-shopper-label :value="__('Image preview')" />
                        <div class="mt-1">
                            <x-shopper-input.single-upload id="file" wire:click="removeImage" wire:model="file" :file="$file" :error="$errors->first('file')" />
                        </div>
                        @if($media)
                            <div class="flex items-center justify-between mt-4 p-2 bg-gray-50 rounded-md border border-dashed border-gray-200 dark:bg-gray-700 dark:border-gray-600">
                                <div class="flex flex-1 items-center space-x-2 truncate">
                                    <div class="flex-shrink-0 w-10 h-10 overflow-hidden rounded-md shadow-md">
                                        <img class="h-full w-full object-cover" src="{{ $media->file_path }}" alt="">
                                    </div>
                                    <div class="truncate">
                                        <h4 class="text-sm leading-5 text-gray-500 truncate dark:text-gray-400">{{ $media->file_name }}</h4>
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
                </div>
            </aside>
        </div>
    </div>

</div>
