<x-shopper::container class="space-y-8">
    <div>
        <x-filament::section.heading>
            {{ __('shopper::words.seo.title') }}
        </x-filament::section.heading>
        <x-filament::section.description class="mt-1 max-w-2xl">
            {{ __('shopper::words.seo.description', ['name' => __('shopper::pages/products.single')]) }}
        </x-filament::section.description>
    </div>
    <div class="grid gap-6 lg:grid-cols-2 lg:gap-x-10">
        <form wire:submit="store">
            {{ $this->form }}

            <div class="mt-8">
                <div class="flex justify-end">
                    <x-shopper::buttons.primary type="submit" wire.loading.attr="disabled">
                        <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                        {{ __('shopper::forms.actions.update') }}
                    </x-shopper::buttons.primary>
                </div>
            </div>
        </form>

        <div class="max-w-xl">
            <h4 class="text-sm leading-5 text-gray-600 dark:text-gray-400">
                {{ __('shopper::words.seo.sub_description') }}
            </h4>
            <div
                class="mt-5 flex h-auto flex-col overflow-hidden rounded-xl bg-gray-100 p-1 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-white/10"
            >
                <div class="flex w-full items-center justify-between p-1.5">
                    <div class="flex items-center space-x-2">
                        <div class="h-3 w-3 rounded-full border border-red-400 bg-red-500"></div>
                        <div class="h-3 w-3 rounded-full border border-yellow-400 bg-yellow-500"></div>
                        <div class="h-3 w-3 rounded-full border border-green-400 bg-green-500"></div>
                    </div>
                    <x-untitledui-google-chrome class="size-5 text-gray-500 dark:text-gray-300" />
                </div>
                <div class="mt-2 h-full w-full overflow-auto rounded-lg bg-white p-4 dark:bg-gray-950 sm:p-6">
                    <div class="flex flex-col">
                        <h3 class="font-medium leading-6 text-primary-600 dark:text-primary-500">
                            {{ $data['seo_title'] }}
                        </h3>
                        <span class="mt-1 truncate text-sm leading-5 text-green-600 dark:text-green-400">
                            {{ config('app.url') }}/{your-custom-prefix}/{{ $data['slug'] }}
                        </span>
                        <p class="text-whitespace-no-wrap mt-1 text-sm leading-5 text-gray-500 dark:text-gray-400">
                            {{ $data['seo_description'] }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shopper::container>
