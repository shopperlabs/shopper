@push('styles')
    @once
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/nano.min.css"/>
    @endonce
@endpush

<div class="pb-10">
    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                {{ __('shopper::pages/attributes.values.title') }}
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                {{ __('shopper::pages/attributes.values.description') }}
            </p>
        </div>
    </div>

    <x-shopper::card class="mt-5 pt-5 space-y-5 overflow-hidden">
        <div class="px-4 sm:px-5 flex items-center justify-between space-x-4">
            <div>
                <span class="shadow-sm rounded-md">
                    <x-shopper::buttons.primary wire:click="$emit('openModal', 'shopper-modals.create-value', {{ json_encode([$attribute->id]) }})" type="button">
                        {{ __('shopper::words.actions_label.add_new', ['name' => strtolower(__('shopper::layout.forms.label.value'))]) }}
                    </x-shopper::buttons.primary>
                </span>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="overflow-hidden border-t border-gray-200 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700">
                                    <th scope="col" class="pl-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400"></th>
                                    <th scope="col" class="pl-2 pr-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                        {{ __('shopper::layout.forms.label.value') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                        {{ __('shopper::layout.forms.label.key') }}
                                    </th>
                                    <th scope="col" class="relative px-6 py-3 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                                        <span class="sr-only">{{ __('shopper::words.actions') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700" x-max="1">
                                @forelse($values as $v)
                                    <tr>
                                        <td class="pl-6 py-4 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                            <x-shopper::forms.checkbox aria-label="{{ __('shopper::pages/attributes.attribute_value') }}" id="is_filterable_{{ $v->id }}" :value="$v->id" />
                                        </td>
                                        <td class="pl-2 pr-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500 dark:text-gray-400">
                                            {{ $v->value }}
                                        </td>
                                        <td class="px-6 py-4 flex items-center space-x-3 whitespace-no-wrap text-sm leading-5 text-gray-500 dark:text-gray-400">
                                            @if($v->attribute->type === 'colorpicker')
                                                <span class="inline-flex items-center gap-x-2 rounded-full px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-200 dark:text-gray-300 dark:ring-gray-700">
                                                    <x-shopper::icons.contrast class="h-5 w-5" style="color: {{ $v->key }}" />
                                                    <kb>{{ $v->key }}</kb>
                                                </span>
                                            @else
                                                <span>{{ $v->key }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-no-wrap text-right text-sm leading-5 font-medium">
                                            <div class="flex-1 flex justify-end items-center space-x-4">
                                                <button
                                                    wire:key="{{ $v->id }}"
                                                    wire:click="$emit('openModal', 'shopper-modals.update-value', {{ json_encode([$attribute->name, $type, $v->id]) }})"
                                                    type="button"
                                                    class="text-gray-500 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-500"
                                                >
                                                    <x-untitledui-edit-04 class="h-5 w-5" />
                                                </button>
                                                <button wire:click="removeValue({{ $v->id }})" type="button" class="text-red-600 hover:text-red-700 dark:text-red-500">
                                                    <x-untitledui-trash-03 class="h-5 w-5" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium">
                                            <div class="flex flex-col justify-center items-center space-y-2 py-4">
                                                <x-untitledui-file-02 class="h-8 w-8 text-primary-500" />
                                                <span class="text-lg font-medium text-gray-500 dark:text-gray-400">
                                                    {{ __('shopper::words.no_values') }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-shopper::card>
</div>

@push('scripts')
    @once
        <script src="https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js"></script>
    @endonce
@endpush
