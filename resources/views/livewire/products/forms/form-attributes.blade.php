@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

<div
    x-data
    class="bg-white rounded-lg p-4 sm:p-6 shadow-md"
>
    <div class="pb-5 border-b border-gray-200 space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0">
        <div class="flex-1 min-w-0 max-w-2xl">
            <h2 class="text-lg leading-6 font-medium text-gray-900">{{ __("Product Attributes") }}</h2>
            <p class="mt-1 text-sm text-gray-500 leading-5">
                {{ __("All the attributes associated with this product.") }}
            </p>
        </div>
        <div>
            <button wire:click="openModale" type="button" class="inline-flex items-center px-4 py-2 shadow-sm rounded-md border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                {{ __("Add attribute") }}
            </button>
        </div>
    </div>

    <dl class="divide-y divide-gray-200">
        @forelse($productAttributes as $productAttribute)
            <div wire:key="product-attribute-{{ $productAttribute->id }}" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 @if(! $loop->first) sm:pt-5 @endif">
                <dt class="text-sm leading-5 font-medium text-gray-500">
                    {{ __($productAttribute->attribute->name) }}
                </dt>
                <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                    @if($productAttribute->values->count() <= 1)
                        @if(in_array($productAttribute->type, ['richtext', 'markdown']))
                            <div class="flex-grow">
                                {{ str_limit(strip_tags(nl2br($productAttribute->values->first()->real_value)), 157) }}
                            </div>
                        @else
                            <span class="flex-grow">{{ $productAttribute->values->first()->real_value }}</span>
                        @endif
                    @else
                        <span class="flex-grow">WIP</span>
                    @endif
                    <span class="flex-shrink-0 flex items-start space-x-4">
{{--                        <button wire:click="updateProductAttribute({{ $productAttribute->id }})" type="button" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">--}}
{{--                            {{ __('Update') }}--}}
{{--                        </button>--}}
{{--                        <span class="text-gray-300" aria-hidden="true">|</span>--}}
                        <button wire:click="removeProductAttribute({{ $productAttribute->id }})" type="button" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                            {{ __('Remove') }}
                        </button>
                  </span>
                </dd>
            </div>
        @empty
            <div class="py-4 space-y-1 sm:py-6">
                <div class="flex justify-center items-center space-x-2">
                    <svg class="h-8 w-8 text-cool-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span class="font-medium py-8 text-cool-gray-400 text-xl">{{ __("No attributes") }}</span>
                </div>
            </div>
        @endforelse
    </dl>

    <x-shopper-modal wire:model="launchModale" maxWidth="xl">
        <div class="bg-white">
            <div class="sm:flex sm:items-start px-4 sm:px-6 py-4">
                <div class="text-left">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ __("Add new attribute with value") }}
                    </h3>
                </div>
            </div>
            <div class="p-4 sm:px-6 sm:py-5 border-t border-gray-100">
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="attribute_id" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Attribute") }}</label>
                        <div class="mt-1 rounded-md shadow-sm">
                            <select wire:model="attribute_id" id="attribute_id" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                <option value="0">{{ __("Select the attribute to add") }}</option>
                                @foreach($attributes as $attribute)
                                    <option value="{{ $attribute->id }}" @if($loop->first) selected @endif>{{ $attribute->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('attribute_id')
                            <p class="mt-2 text-red-500 text-sm leading-5">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="value" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Value") }}</label>
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
                                    <input wire:model="value" x-ref="input" id="value" type="text" class="form-input block w-full pl-10 sm:text-sm sm:leading-5" placeholder="{{ __("Choose a date") }}" readonly />
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
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <span class="flex w-full sm:ml-3 sm:w-auto">
                <x-shopper-button wire:click="addAttribute" type="button" wire.loading.attr="disabled">
                    <svg wire:loading wire:target="addAttribute" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                    </svg>
                    {{ __("Add attribute") }}
                </x-shopper-button>
            </span>
            <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                <button wire:click="closeModal" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                    {{ __("Cancel") }}
                </button>
            </span>
        </div>
    </x-shopper-modal>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush
