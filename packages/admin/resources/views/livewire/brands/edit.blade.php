<div x-data="{ on: @entangle('is_enabled') }">
    <x-shopper::breadcrumb :back="route('shopper.brands.index')">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper::breadcrumb.link :link="route('shopper.brands.index')" :title="__('shopper::layout.sidebar.brands')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading>
        <x-slot name="title">
            {{ $name }}
        </x-slot>

        <x-slot name="action">
            <span class="shadow-sm rounded-md">
                <x-shopper::buttons.primary wire:click="store" wire.loading.attr="disabled" type="button">
                    <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                    {{ __('shopper::layout.forms.actions.update') }}
                </x-shopper::buttons.primary>
            </span>
        </x-slot>
    </x-shopper::heading>

    <div class="mt-6 space-y-5 lg:space-y-0 lg:grid lg:grid-cols-6 lg:gap-6">
        <div class="lg:col-span-4 space-y-5">
            <div class="bg-white dark:bg-secondary-800 rounded-lg shadow p-4 sm:p-5">
                <div>
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.name')" for="name" isRequired :error="$errors->first('name')">
                        <x-shopper::forms.input wire:model.defer="name" id="name" type="text" autocomplete="off" placeholder="Apple, Nike, Samsung..." />
                    </x-shopper::forms.group>
                </div>
                <div class="mt-4">
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.website')" for="website">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-secondary-500 dark:text-secondary-400 sm:text-sm sm:leading-5">https://</span>
                        </div>
                        <x-shopper::forms.input wire:model.defer="website" id="website" type="text" class="pl-16" placeholder="www.example.com" />
                    </x-shopper::forms.group>
                </div>
                <div class="mt-5 border-t border-b border-secondary-200 dark:border-secondary-700 py-4">
                    <div class="relative flex items-start">
                        <div class="flex items-center h-5">
                            <span wire:model="is_enabled" role="checkbox" tabindex="0" x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-secondary-200': !on, 'bg-primary-600': on }" class="bg-secondary-200 relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-brand">
                                <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"></span>
                            </span>
                        </div>
                        <div class="ml-3 text-sm leading-5">
                            <x-shopper::label for="online" :value="__('shopper::layout.forms.label.visibility')" />
                            <p class="text-sm text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::words.set_visibility', ['name' => strtolower(__('shopper::words.brand'))]) }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5">
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.description')" for="description">
                        <livewire:shopper-forms.trix :value="$description" />
                    </x-shopper::forms.group>
                </div>
            </div>

            <x-shopper::forms.seo
                slug="brands"
                :title="$seoTitle"
                :url="$brand->slug"
                :description="$seoDescription"
                :canUpdate="$updateSeo"
            />
        </div>
        <div class="lg:col-span-2">
            <aside class="sticky top-6 space-y-5">
                <div class="bg-white dark:bg-secondary-800 rounded-md shadow overflow-hidden divide-y divide-secondary-200 dark:divide-secondary-700">
                    <div class="p-4 sm:p-5">
                        <x-shopper::label :value="__('shopper::layout.forms.label.image_preview')" />
                        <div class="mt-1">
                            <livewire:shopper-forms.uploads.single :media="$brand->getFirstMedia(config('shopper.system.storage.disks.uploads'))" />
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>
