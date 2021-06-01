<div class="p-4 sm:p-5 bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden space-y-8">
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ __('Search Engine Optimization') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
            {{ __('Improve your ranking and how your product page will appear in search engines results.') }}
        </p>
        <p class="mt-1 inline-flex text-sm text-gray-500 dark:text-gray-400 leading-5 border-dashed border-b border-gray-300 dark:border-gray-700">
            {{ __('Here is a preview of your search engine result, play with it!') }}
        </p>
    </div>
    <div class="grid gap-4 sm:grid-cols-4 sm:gap-6">
        <div class="sm:col-span-2">
            <div class="space-y-5">
                <x-shopper-input.group label="Title">
                    <x-shopper-input.text wire:model.debounce.350ms="seoTitle" id="seo_title" type="text" autocomplete="off" />
                </x-shopper-input.group>
                <div>
                    <div class="flex items-center justify-between">
                        <x-shopper-label for="seo_description" :value="__('Description')" />
                        <span class="ml-4 text-sm leading-5 text-gray-500 dark:text-gray-400">{{ __('160 characters') }}</span>
                    </div>
                    <div class="mt-1 rounded-md shadow-sm">
                        <x-shopper-input.textarea wire:model.debounce.350ms="seoDescription" id="seo_description" />
                    </div>
                </div>
                <div>
                    <x-shopper-input.group label="Friendly URL" for="slug" isRequired :error="$errors->first('slug')">
                        <x-shopper-input.text wire:model="slug" id="slug" type="text" autocomplete="off" placeholder="my-custom-url" />
                    </x-shopper-input.group>
                </div>
            </div>
            <div class="mt-5 pt-5 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-end">
                    <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                        <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                        {{ __('Update') }}
                    </x-shopper-button>
                </div>
            </div>
        </div>
        <div class="sm:col-span-2 mt-6">
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-600 dark:to-gray-700 h-auto flex flex-col rounded-md overflow-hidden">
                <div class="overflow-hidden border border-gray-200 dark:border-gray-600 border-opacity-75 rounded-md shadow container mx-auto h-full">
                    <div class="flex items-center justify-between w-full p-3 bg-gray-100 dark:bg-gray-700">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-300 border border-red-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-300 border border-yellow-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-300 border border-green-400 rounded-full"></div>
                        </div>
                    </div>
                    <div class="w-full h-full overflow-auto p-4 sm:p-6">
                        <div class="flex flex-col">
                            <h3 class="text-base text-blue-800 dark:text-blue-500 font-medium leading-6">{{ $seoTitle }}</h3>
                            <span class="mt-1 text-green-600 dark:text-green-400 text-sm leading-5 truncate">{{ env('APP_URL') }}/products/{{ str_slug($slug) }}</span>
                            <p class="mt-1 text-gray-500 dark:text-gray-400 text-sm leading-5 text-whitespace-no-wrap">{{ str_limit($seoDescription, 160) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
