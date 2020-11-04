<div x-data="{ on: false }">
    <x:shopper-breadcrumb back="shopper.categories.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
        <a href="{{ route('shopper.categories.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Categories') }}</a>
    </x:shopper-breadcrumb>

    <div class="mt-3 pb-5 border-b border-gray-200 space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
        <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            {{ __("Create category") }}
        </h3>
        <div class="flex">
            <x-shopper-button wire:click="store" type="button">
                <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                {{ __("Save") }}
            </x-shopper-button>
        </div>
    </div>

    <div class="mt-6 grid sm:grid-cols-6 gap-4 lg:gap-6">
        <div class="sm:col-span-4">
            <div class="bg-white rounded-lg shadow p-4 sm:p-5">
                <div>
                    <x-shopper-input.group label="Name" for="name" isRequired :error="$errors->first('name')">
                        <input wire:model="name" id="name" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" placeholder="Women Shoes, Baby Clothes clothes">
                    </x-shopper-input.group>
                </div>
                <div class="mt-4">
                    <label for="parentId" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Parent") }}</label>
                    <div class="mt-1 rounded-md shadow-sm">
                        <select wire:model="parentId" id="parentId" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            <option>{{ __("Select parent category") }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-5 border-t border-b border-gray-200 py-4">
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <span wire:model="is_enabled" role="checkbox" tabindex="0" x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-gray-200': !on, 'bg-blue-600': on }" class="bg-gray-200 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-brand">
                                <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"></span>
                            </span>
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <label for="online" class="font-medium text-gray-700">{{ __("Visibility") }}</label>
                            <p class="text-sm text-gray-400 font-normal">{{ __("Set category visibility for the customers.") }}</p>
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
                <div class="bg-white rounded-md shadow overflow-hidden divide-y divide-gray-200">
                    <div class="p-4 sm:p-5">
                        <x-shopper-input.group label="Slug (url)" for="slug" isRequired :error="$errors->first('slug')">
                            <input wire:model="slug" id="slug" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off">
                        </x-shopper-input.group>
                    </div>
                    <div class="p-4 sm:p-5">
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
                </div>
            </aside>
        </div>
    </div>

</div>
