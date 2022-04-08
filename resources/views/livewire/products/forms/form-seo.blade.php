<div class="p-4 sm:p-5 bg-white dark:bg-secondary-800 rounded-lg shadow-md overflow-hidden space-y-8">
    <div>
        <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">
            {{ __('Search Engine Optimization') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-secondary-500 dark:text-secondary-400">
            {{ __('Improve your ranking and how your product page will appear in search engines results.') }}
        </p>
        <p class="mt-1 inline-flex text-sm text-secondary-500 dark:text-secondary-400 leading-5 border-dashed border-b border-secondary-300 dark:border-secondary-700">
            {{ __('Here is a preview of your search engine result, play with it!') }}
        </p>
    </div>
    <div class="grid gap-4 sm:grid-cols-4 sm:gap-6">
        <div class="sm:col-span-2">
            <div class="space-y-5">
                <x-shopper::forms.group label="Title">
                    <x-shopper::forms.input wire:model.defer="seoTitle" id="seo_title" type="text" autocomplete="off" />
                </x-shopper::forms.group>
                <div>
                    <div class="flex items-center justify-between">
                        <x-shopper::label for="seo_description" :value="__('Description')" />
                        <span class="ml-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ __('160 characters') }}</span>
                    </div>
                    <div class="mt-1 rounded-md shadow-sm">
                        <x-shopper::forms.textarea wire:model.defer="seoDescription" id="seo_description" />
                    </div>
                </div>
                <div>
                    <x-shopper::forms.group label="Friendly URL" for="slug" isRequired :error="$errors->first('slug')">
                        <x-shopper::forms.input wire:model.defer="slug" id="slug" type="text" autocomplete="off" placeholder="my-custom-url" />
                    </x-shopper::forms.group>
                </div>
            </div>
            <div class="mt-5 pt-5 border-t border-secondary-200 dark:border-secondary-700">
                <div class="flex justify-end">
                    <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                        <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                        {{ __('Update') }}
                    </x-shopper::buttons.primary>
                </div>
            </div>
        </div>
        <div class="sm:col-span-2 mt-6">
            <div class="bg-gradient-to-br from-secondary-50 to-secondary-100 dark:from-secondary-600 dark:to-secondary-700 h-auto flex flex-col rounded-md overflow-hidden">
                <div class="overflow-hidden border border-secondary-200 dark:border-secondary-600 border-opacity-75 rounded-md shadow container mx-auto h-full">
                    <div class="flex items-center justify-between w-full p-3 bg-secondary-100 dark:bg-secondary-700">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-red-300 border border-red-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-300 border border-yellow-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-300 border border-green-400 rounded-full"></div>
                        </div>
                    </div>
                    <div class="w-full h-full overflow-auto p-4 sm:p-6">
                        <div class="flex flex-col">
                            <h3 class="text-base text-primary-600 font-medium leading-6">{{ $seoTitle }}</h3>
                            <span class="mt-1 text-green-600 dark:text-green-400 text-sm leading-5 truncate">{{ config('app.url') }}/products/{{ $slug }}</span>
                            <p class="mt-1 text-secondary-500 dark:text-secondary-400 text-sm leading-5 text-whitespace-no-wrap">{{ str_limit($seoDescription, 160) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
