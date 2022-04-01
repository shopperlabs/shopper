<div>
    <x:shopper-breadcrumb back="shopper.collections.index">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.collections.index')" title="Collections" />
    </x:shopper-breadcrumb>

    <x-shopper-heading class="mt-3">
        <x-slot name="title">
            {{ $name }}
        </x-slot>

        <x-slot name="action">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                {{ __('Update') }}
            </x-shopper-button>
        </x-slot>
    </x-shopper-heading>

    <div class="mt-6 grid sm:grid-cols-6 gap-4 lg:gap-6">
        <div class="sm:col-span-4 space-y-5">
            <div class="bg-white rounded-lg shadow p-4 sm:p-5 dark:bg-secondary-800">
                <x-shopper-forms.group label="Name" for="name" isRequired :error="$errors->first('name')">
                    <x-shopper-forms.input wire:model.defer="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Summers Collections, Christmas promotions...') }}" />
                </x-shopper-forms.group>
                <div class="mt-5">
                    <x-shopper-forms.group label="Description" for="description">
                        <livewire:shopper-forms.trix :value="$description" />
                    </x-shopper-forms.group>
                </div>
            </div>

            <livewire:shopper-collections.products :collection="$collection" />

            <x-shopper-forms.seo
                slug="collections"
                :title="$seoTitle"
                :url="$collection->slug"
                :description="$seoDescription"
                :canUpdate="$updateSeo"
            />
        </div>
        <div class="sm:col-span-2">
            <aside class="sticky top-6 space-y-5">
                <div class="bg-white rounded-md shadow p-4 sm:p-5 dark:bg-secondary-800">
                    <x-datetime-picker
                        :label="__('Collection availability')"
                        :placeholder="__('Pick a date')"
                        wire:model="publishedAt"
                        parse-format="YYYY-MM-DD HH:mm"
                        time-format="24"
                        without-timezone
                        class="dark:bg-secondary-700"
                    />
                    @if($publishedAt)
                        <div class="mt-2 flex items-start">
                            <div class="mt-1 shrink-0 w-2.5 h-2.5 rounded-full bg-primary-600"></div>
                            <p class="ml-2.5 text-sm text-secondary-500 leading-5 dark:text-secondary-400">
                                {{ __('Will be published on:') }} <br>
                                {{ $publishedAtFormatted }}
                            </p>
                        </div>
                    @else
                        <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                            {{ __('Specify a publication date so that your collections are scheduled on your store.') }}
                        </p>
                    @endif
                </div>
                <div class="bg-white rounded-md shadow overflow-hidden p-4 sm:p-5 dark:bg-secondary-800">
                    <h4 class="block text-sm font-medium leading-5 text-secondary-700 dark:text-secondary-300">{{ __('Image preview') }}</h4>
                    <div class="mt-1">
                        <livewire:shopper-forms.uploads.single :media="$collection->getFirstMedia(config('shopper.system.storage.disks.uploads'))" />
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <div class="mt-6 border-t border-secondary-200 pt-5 pb-10 dark:border-secondary-700">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                {{ __('Update') }}
            </x-shopper-button>
        </div>
    </div>
</div>
