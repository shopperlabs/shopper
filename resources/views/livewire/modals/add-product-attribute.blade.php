<x-shopper-modal
    headerClasses="p-4 sm:px-6 sm:py-4 border-b border-secondary-100 dark:border-secondary-700"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="title">
        {{ __('Add new attribute with value') }}
    </x-slot>

    <x-slot name="content">
        <div class="grid gap-4 sm:grid-cols-2">
            <x-shopper-forms.group label="Attribute" for="attribute_id" class="sm:col-span-2" :error="$errors->first('attribute_id')" isRequired>
                <x-shopper-forms.select wire:model="attribute_id" id="attribute_id">
                    <option value="0">{{ __('Select the attribute to add') }}</option>
                    @foreach($attributes as $attribute)
                        <option value="{{ $attribute['id'] }}" @if($loop->first) selected @endif>{{ $attribute['name'] }}</option>
                    @endforeach
                </x-shopper-forms.select>
            </x-shopper-forms.group>
            <div class="sm:col-span-2">
                <x-shopper-label for="value" value="{{ __('Value') }}" />
                <div class="mt-1">
                    @if($type === 'text')
                        <x-shopper-forms.input wire:model.lazy="value" id="value" type="text" autocomplete="off" required />
                    @elseif($type === 'number')
                        <x-shopper-forms.input wire:model.lazy="value" id="value" type="number" min="0" step="1" autocomplete="off" required />
                    @elseif($type === 'datepicker')
                        <div x-data x-init="flatpickr($refs.input, {dateFormat: 'Y-m-d'});" class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <x-heroicon-o-calendar class="h-5 w-5 text-secondary-400" />
                            </div>
                            <input wire:model="value" x-ref="input" id="value" type="text" class="w-full pl-10 block w-full dark:bg-secondary-700 dark:text-white placeholder-secondary-500 dark:placeholder-secondary-400 rounded-md shadow-sm border-secondary-300 dark:border-secondary-700 focus:border-primary-300 focus:ring focus:ring-primary-300 dark:focus:ring-offset-secondary-900 focus:ring-opacity-50 sm:text-sm" placeholder="{{ __('Choose a date') }}" readonly />
                        </div>
                    @elseif($type === 'richtext')
                        <livewire:shopper-forms.trix :value="$value" />
                    @elseif($type === 'select')
                        <x-shopper-forms.select wire:model.lazy="value" id="value" required>
                            @foreach($values as $v)
                                <option value="{{ $v->id }}">{{ $v->value }}</option>
                            @endforeach
                        </x-shopper-forms.select>
                    @elseif($type === 'checkbox' || $type === 'colorpicker')
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            @foreach($values as $v)
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <x-shopper-forms.checkbox wire:model="multipleValues" id="value_{{ $v->id }}" value="{{ $v->id }}" />
                                    </div>
                                    <div class="ml-3 text-sm leading-5">
                                        <label for="value_{{ $v->id }}" class="font-medium text-secondary-700 cursor-pointer dark:text-secondary-400">{{ $v->value }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif($type === 'radio')
                        <div class="grid grid-cols-3 gap-4 mt-2">
                            @foreach($values as $v)
                                <div class="flex items-center">
                                    <x-shopper-forms.radio wire:model="value" id="value_{{ $v->id }}" value="{{ $v->id }}" />
                                    <label for="value_{{ $v->id }}" class="ml-3">
                                        <span class="block text-sm leading-5 font-medium text-secondary-700 dark:text-secondary-400">{{ $v->value }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                @error('value')
                    <p class="mt-2 text-red-500 text-sm leading-5">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </x-slot>

    <x-slot name="buttons">
        <span class="flex w-full sm:ml-3 sm:w-auto">
            <x-shopper-button wire:click="save" type="button" wire.loading.attr="disabled">
                <x-shopper-loader wire:loading wire:target="save" class="text-white" />
                {{ __('Add attribute') }}
            </x-shopper-button>
        </span>
        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
            <x-shopper-default-button wire:click="closeModal" type="button">
                {{ __('Cancel') }}
            </x-shopper-default-button>
        </span>
    </x-slot>

</x-shopper-modal>
