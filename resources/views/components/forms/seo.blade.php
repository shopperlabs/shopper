@props(['slug', 'title', 'description', 'url', 'canUpdate'])

<div class="bg-white rounded-lg shadow-md divide-y divide-secondary-200 dark:bg-secondary-800 dark:divide-secondary-700">
    <div class="p-4 sm:p-5">
        <div class="flex items-center justify-between">
            <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">{{ __('Search engine listing preview') }}</h3>
            @if(! $canUpdate)
                <button wire:click="updateSeo" type="button" class="text-sm leading-5 bg-transparent outline-none focus:outline-none text-primary-600 hover:text-primary-700 dark:text-primary-500/50">{{ __('Edit SEO preview') }}</button>
            @endif
        </div>
        <div class="mt-4">
            @if(! $canUpdate)
                <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ __('Add a title and description to see how this collection might appear in a search engine listing.') }}</p>
            @else
                <div class="flex flex-col">
                    <h3 class="text-base text-primary-800 font-medium leading-6 dark:text-primary-500/50">{{ $title }}</h3>
                    <span class="mt-1 text-green-600 text-sm leading-5 dark:text-green-500/50">{{ config('app.url') }}/{{ $slug }}/{{ $url }}</span>
                    <p class="mt-1 text-secondary-500 text-sm leading-5 dark:text-secondary-400">{{ str_limit($description, 160) }}</p>
                </div>
            @endif
        </div>
    </div>
    @if($canUpdate)
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
