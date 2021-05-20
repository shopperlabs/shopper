<x-shopper-modal
    headerClasses="bg-white p-4 sm:px-6 sm:py-4 border-b border-gray-100"
    contentClasses="relative p-4 sm:px-6 sm:px-5"
    footerClasses="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
>

    <x-slot name="title">
        {{ __('Add new attribute with value') }}
    </x-slot>

    <x-slot name="content">
        <div class="grid gap-4 sm:grid-cols-2">
            <x-shopper-input.group label="Attribute" for="attribute_id" class="sm:col-span-2" :error="$errors->first('attribute_id')" isRequired>
                <x-shopper-input.select wire:model="attribute_id" id="attribute_id">
                    <option value="0">{{ __('Select the attribute to add') }}</option>
                    @foreach($attributes as $attribute)
                        <option value="{{ $attribute['id'] }}" @if($loop->first) selected @endif>{{ $attribute['name'] }}</option>
                    @endforeach
                </x-shopper-input.select>
            </x-shopper-input.group>
            <div class="sm:col-span-2">
                <x-shopper-label for="value" value="{{ __('Value') }}" />
                <div class="mt-1">
                    @if($type === 'text')
                        <input wire:model="value" id="value" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" required />
                    @elseif($type === 'number')
                        <input wire:model="value" id="value" type="number" min="0" step="1" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" required />
                    @elseif($type === 'datepicker')
                        <div x-data x-init="flatpickr($refs.input, {dateFormat: 'Y-m-d'});" class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input wire:model="value" x-ref="input" id="value" type="text" class="form-input block w-full pl-10 sm:text-sm sm:leading-5" placeholder="{{ __('Choose a date') }}" readonly />
                        </div>
                    @elseif($type === 'richtext')
                        <div
                            wire:ignore
                            class="rounded-md shadow-sm"
                            wire:model="value"
                            id="value"
                            x-data
                            @trix-blur="$dispatch('change', $event.target.value)"
                        >
                            <input id="richtext" value="{{ $value }}" type="hidden" class="sr-only">
                            <trix-editor input="richtext" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"></trix-editor>
                        </div>
                    @elseif($type === 'markdown')
                        <div
                            x-data
                            x-init="
                                (function(easyMDE) {
                                    easyMDE.codemirror.on('change', function () {
                                        @this.set('value', easyMDE.value());
                                    });
                                } (new EasyMDE({ element: $refs.textarea })))
                            "
                            wire:ignore
                            {{ $attributes }}
                        >
                            <textarea name="body" id="body" x-ref="textarea" class="form-input block w-full sm:text-sm sm:leading-5">{{ $value }}</textarea>
                        </div>
                    @elseif($type === 'select')
                        <select wire:model="value" id="value" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" required>
                            @foreach($values as $v)
                                <option value="{{ $v->id }}">{{ $v->value }}</option>
                            @endforeach
                        </select>
                    @elseif($type === 'checkbox' || $type === 'checkbox_list')
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            @foreach($values as $v)
                                <div class="relative flex items-start">
                                    <div class="flex items-center h-5">
                                        <input wire:model="multipleValues" id="value_{{ $v->id }}" value="{{ $v->id }}" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out" />
                                    </div>
                                    <div class="ml-3 text-sm leading-5">
                                        <label for="value_{{ $v->id }}" class="font-medium text-gray-700 cursor-pointer">{{ $v->value }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif($type === 'radio')
                        <div class="grid grid-cols-3 gap-4 mt-2">
                            @foreach($values as $v)
                                <div class="flex items-center">
                                    <input wire:model="value" id="value_{{ $v->id }}" type="radio" value="{{ $v->id }}" class="form-radio h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                    <label for="value_{{ $v->id }}" class="ml-3">
                                        <span class="block text-sm leading-5 font-medium text-gray-700">{{ $v->value }}</span>
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
