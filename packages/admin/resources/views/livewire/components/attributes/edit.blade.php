<x-shopper::container>
    <div
        x-data="{ on: @entangle('isEnabled'), show: false }"
        x-init="
            @this.on('notify-error', () => {
                if (show === false) setTimeout(() => { show = false }, 2500);
                show = true;
            })
       "
    >
        <x-shopper::breadcrumb :back="route('shopper.attributes.index')" :current="$name">
            <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-gray-300 dark:text-gray-600" />
            <x-shopper::breadcrumb.link :link="route('shopper.attributes.index')" :title="__('shopper::words.attributes')" />
        </x-shopper::breadcrumb>

        <x-shopper::heading class="mt-5">
            <x-slot name="title">
                {{ $name }}
            </x-slot>

            <x-slot name="action">
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
            </x-slot>
        </x-shopper::heading>

        @include('shopper::livewire.components.attributes._form')

        @if($this->hasValues())
            <livewire:shopper-attributes.values :attribute="$attribute" />
        @endif
    </div>
</x-shopper::container>
