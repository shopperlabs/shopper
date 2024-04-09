<x-shopper::container class="space-y-8">
    <div>
        <h3 class="font-heading leading-6 font-medium text-gray-900 dark:text-white">
            {{ __('shopper::pages/products.seo.title') }}
        </h3>
        <p class="mt-1 max-w-2xl text-sm leading-5 text-gray-500 dark:text-gray-400">
            {{ __('shopper::pages/products.seo.description') }}
        </p>
    </div>
    <div class="grid gap-6 lg:grid-cols-2 lg:gap-x-10">
        <form wire:submit="store">
            {{ $this->form }}

            <div class="mt-8 pt-10 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-end">
                    <x-shopper::buttons.primary type="submit" wire.loading.attr="disabled">
                        <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                        {{ __('shopper::layout.forms.actions.update') }}
                    </x-shopper::buttons.primary>
                </div>
            </div>
        </form>

        <div class="max-w-xl">
            <h4 class="text-sm leading-5 text-gray-600 dark:text-gray-400">
                {{ __('shopper::pages/products.seo.sub_description') }}
            </h4>
            <div class="mt-5 h-auto flex flex-col bg-gray-100 p-1 ring-1 ring-gray-200 rounded-xl shadow-sm overflow-hidden dark:bg-gray-800 dark:ring-gray-700">
                <div class="flex items-center justify-between w-full p-1.5">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-red-500 border border-red-400 rounded-full"></div>
                        <div class="w-3 h-3 bg-yellow-500 border border-yellow-400 rounded-full"></div>
                        <div class="w-3 h-3 bg-green-500 border border-green-400 rounded-full"></div>
                    </div>
                    <x-untitledui-google-chrome class="h-5 w-5 text-gray-500 dark:text-gray-300" />
                </div>
                <div class="mt-2 w-full h-full overflow-auto rounded-lg bg-white p-4 dark:bg-gray-950 sm:p-6">
                    <div class="flex flex-col">
                        <h3 class="text-primary-600 font-medium leading-6 dark:text-primary-500">
                            {{ $data['seo_title'] }}
                        </h3>
                        <span class="mt-1 text-green-600 text-sm leading-5 truncate dark:text-green-400">
                            {{ config('app.url') }}/{your-custom-prefix}/{{ $data['slug'] }}
                        </span>
                        <p class="mt-1 text-gray-500 text-sm leading-5 text-whitespace-no-wrap dark:text-gray-400">
                            {{ $data['seo_description'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shopper::container>
