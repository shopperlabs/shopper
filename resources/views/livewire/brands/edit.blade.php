<div x-data="{ on: @entangle('is_enabled') }">
    <x:shopper-breadcrumb back="shopper.brands.index">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.brands.index')" title="Brands" />
    </x:shopper-breadcrumb>

    <x-shopper-heading>
        <x-slot name="title">
            {{ $name }}
        </x-slot>

        <x-slot name="action">
            <span class="shadow-sm rounded-md">
                <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                    <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                    {{ __('Update') }}
                </x-shopper-button>
            </span>
        </x-slot>
    </x-shopper-heading>

    <div class="mt-6 grid sm:grid-cols-6 gap-4 lg:gap-6">
        <div class="sm:col-span-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 sm:p-5">
                <div>
                    <x-shopper-input.group label="Name" for="name" isRequired :error="$errors->first('name')">
                        <x-shopper-input.text wire:model.lazy="name" id="name" type="text" autocomplete="off" placeholder="Apple, Nike, Samsung..." />
                    </x-shopper-input.group>
                </div>
                <div class="mt-4">
                    <x-shopper-input.group label="Website" for="website">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm sm:leading-5">https://</span>
                        </div>
                        <x-shopper-input.text wire:model.defer="website" id="website" type="text" class="pl-16" placeholder="www.example.com" />
                    </x-shopper-input.group>
                </div>
                <div class="mt-5 border-t border-b border-gray-200 dark:border-gray-700 py-4">
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <span wire:model="is_enabled" role="checkbox" tabindex="0" x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-gray-200': !on, 'bg-blue-600': on }" class="bg-gray-200 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-brand">
                                <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"></span>
                            </span>
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <x-shopper-label for="online" :value="__('Visibility')" />
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Set brand visibility for the customers.') }}</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <x-shopper-input.group label="Description" for="description">
                        <x-shopper-input.rich-text wire:model.lazy="description" id="description" :initial-value="$description" />
                    </x-shopper-input.group>
                </div>
            </div>
        </div>
        <div class="sm:col-span-2">
            <aside class="sticky top-6 space-y-5">
                <div class="bg-white dark:bg-gray-800 rounded-md shadow overflow-hidden divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="p-4 sm:p-5">
                        <x-shopper-label :value="__('Image preview')" />
                        <div class="mt-1">
                            <x-shopper-input.single-upload id="file" wire:click="removeImage" wire:model="file" :file="$file" :error="$errors->first('file')" />
                        </div>
                        @if($media)
                            <div class="mt-4 p-2 bg-gray-50 dark:bg-gray-700 rounded-md border border-dashed border-gray-200 dark:border-gray-600 flex items-center justify-between">
                                <div class="flex flex-1 items-center space-x-2 truncate">
                                    <div class="flex-shrink-0 w-10 h-10 overflow-hidden rounded-md">
                                        <img class="h-full w-full object-cover" src="{{ $media->image_full_path }}" alt="">
                                    </div>
                                    <div class="truncate">
                                        <h4 class="text-sm leading-5 text-gray-500 dark:text-gray-400 truncate">{{ $media->file_name }}</h4>
                                        <p class="text-xs leading-4 text-gray-400 dark:text-gray-500">{{ $media->file_size }}</p>
                                    </div>
                                </div>
                                <button wire:click="deleteImage({{ $media->id }})" wire:loading.attr="disabled" type="button" class="ml-4 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                                    <x-shopper-loader wire:loading wire:target="deleteImage" class="text-white" />
                                    <x-heroicon-o-trash wire:loading.remove class="h-5 w-5" />
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
