<div class="px-4 py-5">
    <div class="flex justify-end">
        <div @class(['w-full', 'lg:max-w-sm' => $type !== 'richtext'])>
            @if($type === 'richtext')
                <livewire:shopper-forms.trix :value="$value" />

                @error('value')
                    <p class="mt-2 text-danger-500 text-sm leading-5">{{ $message }}</p>
                @enderror
            @endif

            @if($type === 'text')
                <div>
                    <x-shopper::forms.input wire:model.defer="value" id="value" type="text" autocomplete="off" required />
                    @error('value')
                        <p class="mt-2 text-danger-500 text-sm leading-5">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            @if($type === 'number')
                <x-inputs.number wire:model.defer="value" id="value" />
            @endif

            @if($type === 'datepicker')
                <x-datetime-picker
                    :placeholder="__('shopper::layout.forms.placeholder.pick_a_date')"
                    wire:model.defer="value"
                    parse-format="YYYY-MM-DD"
                    display-format="{{ config('shopper.admin.date_format') }}"
                    without-time
                    class="dark:bg-secondary-700"
                />
            @endif
        </div>
    </div>

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

</div>
