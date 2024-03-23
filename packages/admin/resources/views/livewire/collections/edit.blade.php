<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.collections.index')">
        <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
        <x-shopper::breadcrumb.link :link="route('shopper.collections.index')" :title="__('shopper::layout.sidebar.collections')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-5">
        <x-slot name="title">
            {{ $name }}
        </x-slot>
    </x-shopper::heading>

    <div class="mt-8 space-y-5 lg:space-y-0 lg:grid lg:grid-cols-6 lg:gap-6">
        <div class="lg:col-span-4 space-y-5">
            <x-shopper::card class="p-4 sm:p-5">
                <x-shopper::forms.group :label="__('shopper::layout.forms.label.name')" for="name" isRequired :error="$errors->first('name')">
                    <x-shopper::forms.input wire:model.defer="name" id="name" type="text" autocomplete="off" placeholder="{{ __('Summers Collections, Christmas promotions...') }}" />
                </x-shopper::forms.group>
                <div class="mt-5">
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.description')" for="description">
                        <livewire:shopper-forms.trix :value="$description" />
                    </x-shopper::forms.group>
                </div>
            </x-shopper::card>

            <livewire:shopper-collections.products :collection="$collection" />

            <x-shopper::forms.seo
                slug="collections"
                :title="$seoTitle"
                :url="$collection->slug"
                :description="$seoDescription"
                :canUpdate="$updateSeo"
            />
        </div>
        <div class="lg:col-span-2">
            <aside class="sticky top-6 space-y-5">
                <x-shopper::card class="p-4 sm:p-5">
                    <x-datetime-picker
                        :label="__('shopper::layout.forms.label.availability')"
                        :placeholder="__('shopper::layout.forms.placeholder.pick_a_date')"
                        wire:model="publishedAt"
                        parse-format="YYYY-MM-DD HH:mm"
                        display-format="{{ config('shopper.admin.date_time_format') }}"
                        time-format="24"
                        class="dark:bg-secondary-700"
                    />
                    @if($publishedAt)
                        <div class="mt-2 flex items-start">
                            <div class="mt-1 shrink-0 w-2.5 h-2.5 rounded-full bg-primary-600"></div>
                            <p class="ml-2.5 text-sm text-secondary-500 leading-5 dark:text-secondary-400">
                                {{ __('shopper::words.published_on') }} <br>
                                {{ $publishedAtFormatted }}
                            </p>
                        </div>
                    @else
                        <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                            {{ __('shopper::pages/collections.availability_description') }}
                        </p>
                    @endif
                </x-shopper::card>
                <x-shopper::card class="overflow-hidden p-4 sm:p-5">
                    <h4 class="block text-sm font-medium leading-5 text-secondary-700 dark:text-secondary-300">
                        {{ __('shopper::layout.forms.label.image_preview') }}
                    </h4>
                    <div class="mt-1">
                        <livewire:shopper-forms.uploads.single :media="$collection->getFirstMedia(config('shopper.core.storage.collection_name'))" />
                    </div>
                </x-shopper::card>
            </aside>
        </div>
    </div>

    <div class="mt-6 border-t border-secondary-200 pt-5 pb-10 dark:border-secondary-700">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.update') }}
            </x-shopper::buttons.primary>
        </div>
    </div>
</x-shopper::container>
