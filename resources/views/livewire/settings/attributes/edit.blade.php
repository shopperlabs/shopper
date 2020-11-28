<div>
    <x:shopper-breadcrumb back="shopper.settings.attributes.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
        </svg>
        <a href="{{ route('shopper.settings.attributes.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Attributes') }}</a>
    </x:shopper-breadcrumb>

    <div class="mt-3 pb-5 border-b border-gray-200 space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
        <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            {{ $name }}
        </h3>
        <div class="flex">
            <x-shopper-button wire:click="store" type="button">
                <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                {{ __("Update") }}
            </x-shopper-button>
        </div>
    </div>

    <div class="mt-6 grid sm:grid-cols-6 gap-4 sm:gap-6">
        <div class="sm:col-span-4">
            <div class="bg-white rounded-lg shadow p-4 sm:p-5 grid gap-4 sm:grid-cols-2 sm:gap-6">
                <div class="sm:col-span-1">
                    <x-shopper-input.group label="Name" for="name" :error="$errors->first('name')">
                        <input wire:model="name" id="name" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off">
                    </x-shopper-input.group>
                </div>
                <div class="sm:col-span-1">
                    <label for="type" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Type") }}</label>
                    <div class="mt-1 rounded-md shadow-sm">
                        <select wire:model="type" id="type" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                            <option value="text">{{ __("Text field") }} (input)</option>
                            <option value="number">{{ __("Text field") }} (number)</option>
                            <option value="richtext">{{ __("Richtext") }}</option>
                            <option value="markdown">{{ __("Markdown") }}</option>
                            <option value="select">{{ __("Select") }}</option>
                            <option value="checkbox">{{ __("Checkbox") }}</option>
                            <option value="checkbox_list">{{ __("Checkbox list") }}</option>
                            <option value="radio">{{ __("Radio") }}</option>
                            <option value="toggle">{{ __("Toggle") }}</option>
                            <option value="colorpicker">{{ __("Color picker") }}</option>
                            <option value="datepicker">{{ __("Date picker") }}</option>
                            <option value="file">{{ __("File") }}</option>
                        </select>
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <div class="flex items-center justify-between">
                        <label for="description" class="block text-sm leading-5 font-medium text-gray-700">
                            {{ __("Description") }}
                        </label>
                        <span class="ml-4 text-sm text-gray-500 leading-5">{{ __("Optional") }}</span>
                    </div>
                    <div class="rounded-md shadow-sm">
                        <textarea wire:model="description" id="description" rows="3" class="form-textarea mt-1 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"></textarea>
                    </div>
                </div>
                <div class="sm:col-span-2 flex items-center space-x-4">
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <input wire:model="isSearchable" id="is_searchable" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out" />
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <label for="is_searchable" class="font-medium text-gray-700 cursor-pointer">{{ __("Is Searchable") }}</label>
                            <p class="text-gray-500">{{ __("You can use this attribute to search and filter product.") }}</p>
                        </div>
                    </div>
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <input wire:model="isFilterable" id="is_filterable" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out" />
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <label for="is_filterable" class="font-medium text-gray-700 cursor-pointer">{{ __("Is Filterable") }}</label>
                            <p class="text-gray-500">{{ __("You can use this attribute as a filter on your front store.") }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sm:col-span-2">
            <aside class="sticky top-6 space-y-5">
                <div class="bg-white rounded-md shadow overflow-hidden divide-y divide-gray-200">
                    <div class="p-4 sm:p-5">
                        <x-shopper-input.group label="Slug (code)" for="slug" :error="$errors->first('slug')">
                            <input wire:model="slug" id="slug" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off">
                        </x-shopper-input.group>
                    </div>
                    <div class="p-4 sm:p-5">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <span wire:model="isEnabled" role="checkbox" tabindex="0" x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" x-data="{ on: @entangle('isEnabled') }" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-gray-200': !on, 'bg-blue-600': on }" class="bg-gray-200 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-brand">
                                    <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                    <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"></span>
                                </span>
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <label for="online" class="font-medium text-gray-700">{{ __("Enabled") }}</label>
                                <p class="text-sm text-gray-400 font-normal">{{ __("Set attribute visibility for the customers.") }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
