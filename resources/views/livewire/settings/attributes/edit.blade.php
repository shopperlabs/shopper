<div
    x-data="{ on: @entangle('isEnabled'), show: false }"
    x-init="
        @this.on('notify-error', () => {
            if (show === false) setTimeout(() => { show = false }, 2500);
            show = true;
        })
   "
>
    <x-shopper::breadcrumb :back="route('shopper.settings.attributes.index')">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.attributes.index')" :title="__('shopper::words.attributes')" />
    </x-shopper::breadcrumb>

    <div class="mt-3 pb-5 border-b border-secondary-200 space-y-3 md:flex md:items-center md:justify-between md:space-y-0 dark:border-secondary-700">
        <h3 class="text-2xl font-bold leading-6 text-secondary-900 sm:text-3xl sm:leading-9 sm:truncate dark:text-white">
            {{ $name }}
        </h3>
        <div class="flex items-center space-x-4">
            <div
                x-show.transition.out.duration.1000ms="show"
                style="display: none;"
                class="rounded-md border border-red-200 bg-red-50 px-3 py-2"
            >
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm leading-5 font-medium text-red-800">
                            {{ __("Can't be updated without values!") }}
                        </p>
                    </div>
                </div>
            </div>
            <x-shopper::buttons.primary wire:click="store" type="button">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::layout.forms.actions.update') }}
            </x-shopper::buttons.primary>
        </div>
    </div>

    <div class="mt-6 space-y-10">
        <div class="grid sm:grid-cols-6 gap-4 sm:gap-6">
            <div class="sm:col-span-4">
                <div class="bg-white rounded-lg shadow p-4 sm:p-5 grid gap-4 sm:grid-cols-2 sm:gap-6 dark:bg-secondary-800">
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.name')" for="name" class="sm:col-span-1" :error="$errors->first('name')" isRequired>
                        <x-shopper::forms.input wire:model="name" id="name" type="text" autocomplete="off" />
                    </x-shopper::forms.group>
                    <x-shopper::forms.group for="type" :label="__('shopper::layout.forms.label.type')" class="sm:col-span-1">
                        <x-shopper::forms.select wire:model="type" id="type">
                            @foreach($fields as $key => $field)
                                <option value="{{ $key }}">{{ $field }}</option>
                            @endforeach
                        </x-shopper::forms.select>
                    </x-shopper::forms.group>
                    <div class="sm:col-span-2">
                        <div class="flex items-center justify-between">
                            <x-shopper::label :value="__('shopper::layout.forms.label.description')" for="description" />
                            <span class="ml-4 text-sm text-secondary-500 leading-5 dark:text-secondary-400">
                                {{ __('shopper::layout.forms.label.optional') }}
                            </span>
                        </div>
                        <div class="rounded-md shadow-sm">
                            <x-shopper::forms.textarea wire:model="description" id="description" />
                        </div>
                    </div>
                    <div class="sm:col-span-2 flex items-center space-x-4">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <x-shopper::forms.checkbox wire:model="isSearchable" id="is_searchable" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <x-shopper::label for="is_searchable" :value="__('shopper::layout.forms.label.is_searchable')" />
                                <p class="text-secondary-500 dark:text-secondary-400">
                                    {{ __('shopper::pages/attributes.searchable_description') }}
                                </p>
                            </div>
                        </div>
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <x-shopper::forms.checkbox wire:model="isFilterable" id="is_filterable" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <x-shopper::label for="is_filterable" :value="__('shopper::layout.forms.label.is_filterable')" />
                                <p class="text-secondary-500 dark:text-secondary-400">
                                    {{ __('shopper::pages/attributes.filtrable_description') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sm:col-span-2">
                <aside class="sticky top-6 space-y-5">
                    <div class="bg-white rounded-md shadow overflow-hidden divide-y divide-secondary-200 dark:bg-secondary-800 dark:divide-secondary-700">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.slug')" for="slug" class="p-4 sm:p-5" :error="$errors->first('slug')" isRequired>
                            <x-shopper::forms.input wire:model="slug" id="slug" type="text" autocomplete="off" />
                        </x-shopper::forms.group>
                        <div class="p-4 sm:p-5">
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <span wire:model="isEnabled" role="checkbox" tabindex="0" x-on:click="$dispatch('input', !on); on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="false" x-bind:class="{ 'bg-secondary-200 dark:bg-secondary-700': !on, 'bg-primary-600': on }" class="bg-secondary-200 relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline-brand">
                                        <input type="hidden" x-ref="input" aria-label="Visible" x-model="on" />
                                        <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="translate-x-0 inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200"></span>
                                    </span>
                                </div>
                                <div class="ml-3 text-sm leading-5">
                                    <x-shopper::label for="online" :value="__('shopper::layout.forms.actions.enabled')"></x-shopper::label>
                                    <p class="text-sm text-secondary-500 dark:text-secondary-400">
                                        {{ __('shopper::pages/attributes.attribute_visibility') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

        @if($this->hasValues())
            <livewire:shopper-settings.attributes.values :attribute="$attribute" />
        @endif
    </div>
</div>
