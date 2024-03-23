<div class="overflow-hidden overflow-x-auto">
    <div class="align-middle inline-block min-w-full">
        <table class="min-w-full divide-y divide-secondary-200 dark:divide-secondary-700">
            <thead>
                <tr class="border-b border-secondary-200 bg-secondary-50 dark:border-secondary-700 dark:bg-secondary-700 divide-x divide-secondary-200 dark:divide-secondary-700">
                    <x-shopper::tables.table-head>
                        <span class="lg:pl-2">{{ __('shopper::layout.forms.label.value') }}</span>
                    </x-shopper::tables.table-head>
                    <x-shopper::tables.table-head class="flex items-center justify-between">
                        {{ __('shopper::words.selection') }}
                        @if(count($selected) > 0 || $this->currentValues->isNotEmpty())
                            <button wire:click="save" type="button" class="inline-flex items-center gap-x-2 text-xs leading-5 text-primary-500 uppercase tracking-wider hover:text-primary-600 font-medium">
                                <x-shopper::loader wire:loading wire:target="save" />
                                {{ __('shopper::layout.forms.actions.apply') }}
                            </button>
                        @endif
                    </x-shopper::tables.table-head>
                </tr>
            </thead>
            <tbody class="divide-y divide-secondary-100 dark:divide-secondary-700">
                @forelse($values as $value)
                    <tr class="divide-x divide-secondary-200 dark:divide-secondary-700">
                        <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-secondary-900 dark:text-white">
                            @if($type === 'colorpicker')
                                <span class="inline-flex items-center gap-x-2 rounded-full px-2 py-1 text-xs font-medium text-secondary-700 ring-1 ring-inset ring-secondary-200 dark:text-secondary-300 dark:ring-secondary-700">
                                    <x-shopper::icons.contrast class="h-5 w-5" style="color: {{ $value->key }}" />
                                    <kb>{{ $value->value }}</kb>
                                </span>
                            @else
                                <span class="text-secondary-500 dark:text-secondary-400">
                                    {{ $value->value }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-secondary-900 dark:text-white">
                            <div class="flex items-center h-5">
                                <x-shopper::forms.checkbox
                                    wire:model.lazy="selected"
                                    id="attribute_value_{{ $value->id }}"
                                    value="{{ $value->id }}"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium">
                            <div class="flex justify-center items-center space-x-2">
                                <x-untitledui-file-02 class="h-5 w-5 text-primary-500" />
                                <span class="leading-6 text-secondary-500 dark:text-secondary-400">
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
