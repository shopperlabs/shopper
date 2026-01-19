<x-shopper::container class="space-y-8">
    <x-shopper::section-heading
        :title="__('shopper::words.seo.title')"
        :description="__('shopper::words.seo.description', ['name' => __('shopper::pages/products.single')])"
    />
    <div class="grid gap-6 lg:grid-cols-2 lg:gap-x-10">
        <form wire:submit="store">
            {{ $this->form }}

            <div class="mt-8">
                <div class="flex justify-end">
                    <x-filament::button type="submit" wire.loading.attr="disabled">
                        <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                        {{ __('shopper::forms.actions.update') }}
                    </x-filament::button>
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
                        <div class="size-3 rounded-full bg-red-500"></div>
                        <div class="size-3 rounded-full bg-yellow-500"></div>
                        <div class="size-3 rounded-full bg-green-500"></div>
                    </div>
                    <x-untitledui-google-chrome class="size-5 text-gray-500 dark:text-gray-300" strike-width="1.5" aria-hidden="true" />
                </div>
                <div class="mt-1 rounded-lg p-4 bg-white ring-1 ring-gray-200 dark:bg-gray-950 dark:ring-white/20 size-full overflow-auto">
                    <div class="flex flex-col">
                        <h3 class="text-primary-600 dark:text-primary-500 leading-6 font-medium">
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
