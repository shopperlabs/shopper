<div class="py-5 px-4">
    <div class="grid gap-x-4 gap-y-5 sm:grid-cols-2 lg:gap-x-6 lg:grid-cols-4">
        @forelse($values as $v)
            <div class="flex items-center gap-x-2 h-5">
                <x-shopper::forms.radio
                    wire:model.lazy="value"
                    id="attribute_single_value_{{ $v->id }}"
                    value="{{ $v->id }}"
                />
                <x-shopper::label :value="$v->value" for="attribute_single_value_{{ $v->id }}" />
            </div>
        @empty
            <div class="sm:col-span-2 lg:col-span-4 flex justify-center items-center space-x-2">
                <x-untitledui-file-02 class="h-5 w-5 text-primary-500" />
                <span class="leading-6 text-gray-500 dark:text-gray-400">
                    {{ __('shopper::words.no_values') }}
                </span>
            </div>
        @endforelse
    </div>

    @if($values->isNotEmpty())
        <div class="mt-4 flex items-center justify-end gap-x-3">
            @if($model)
                <x-shopper::buttons.danger-outline wire:click="remove({{ $model->id }})" type="button">
                    {{ __('shopper::layout.forms.actions.remove') }}
                </x-shopper::buttons.danger-outline>
            @endif
            <x-shopper::buttons.primary wire:click="save" type="button" wire.loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="save" class="text-white" />
                {{ __('shopper::layout.forms.actions.save') }}
            </x-shopper::buttons.primary>
        </div>
    @endif
</div>
