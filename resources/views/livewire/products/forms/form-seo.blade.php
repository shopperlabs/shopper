<div class="p-4 sm:p-5 bg-white rounded-lg shadow-md overflow-hidden space-y-8">
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ __("Search Engine Optimization") }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            {{ __("Improve your ranking and how your product page will appear in search engines results.") }}
        </p>
        <p class="mt-1 inline-flex text-sm text-gray-500 leading-5 border-dashed border-b border-gray-300">
            {{ __("Here is a preview of your search engine result, play with it!") }}
        </p>
    </div>
    <div class="grid gap-4 sm:grid-cols-4 sm:gap-6">
        <div class="sm:col-span-2">
            <div class="space-y-5">
                <div>
                    <label for="seo_title" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Title") }}</label>
                    <div class="mt-1 rounded-md shadow-sm">
                        <input
                            wire:model="seoTitle"
                            id="seo_title"
                            type="text"
                            class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                            autocomplete="off"
                        />
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between">
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
                <div>
                    <x-shopper-input.group label="Friendly URL" for="slug" isRequired :error="$errors->first('slug')">
                        <input wire:model="slug" id="slug" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" placeholder="my-custom-url">
                    </x-shopper-input.group>
                </div>
            </div>
            <div class="mt-5 pt-5 border-t border-gray-200">
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
        <div class="sm:col-span-2 mt-6">
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 h-auto flex flex-col rounded-md overflow-hidden">
                <div class="overflow-hidden border border-gray-200 border-opacity-75 rounded-md shadow container mx-auto h-full">
                    <div class="flex items-center justify-between w-full p-3 bg-gray-100">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-300 border border-red-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-300 border border-yellow-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-300 border border-green-400 rounded-full"></div>
                        </div>
                    </div>
                    <div class="w-full h-full overflow-auto p-4 sm:p-6">
                        <div class="flex flex-col">
                            <h3 class="text-base text-blue-800 font-medium leading-6">{{ $seoTitle }}</h3>
                            <span class="mt-1 text-green-600 text-sm leading-5 truncate">{{ env('APP_URL') }}/products/{{ str_slug($slug) }}</span>
                            <p class="mt-1 text-gray-500 text-sm leading-5 text-whitespace-no-wrap">{{ str_limit($seoDescription, 160) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
