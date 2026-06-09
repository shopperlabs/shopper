<div>
    <h3 class="text-lg leading-6 font-medium text-sh-fg">
        {{ __('shopper::pages/orders.private_notes') }}
    </h3>
    <div class="mt-5 flex space-x-3">
        <div class="shrink-0">
            <img
                class="flex size-10 items-center justify-center rounded-full bg-sh-fg-muted ring-4 ring-sh-surface"
                src="{{ shopper()->auth()->user()->picture }}"
                alt="Customer avatar"
            />
        </div>
        <div class="min-w-0 flex-1">
            @if ($order->notes)
                <p class="text-sm text-sh-fg-muted">
                    {{ $order->notes }}
                </p>
            @else
                <div>
                    <label for="comment" class="sr-only">
                        {{ __('shopper::forms.label.comment') }}
                    </label>
                    <x-shopper::forms.textarea
                        wire:model="notes"
                        id="comment"
                        :placeholder="__('shopper::forms.placeholder.leave_comment')"
                        :value="$order->notes"
                    />
                    @error('notes')
                        <p class="text-danger-500 mt-1 text-sm">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
                <div class="mt-6 flex items-center justify-end space-x-4">
                    <x-filament::button
                        wire:click="leaveNotes"
                        wire:loading.attr="disabled"
                        type="button"
                    >
                        <x-shopper::loader wire:loading wire:target="leaveNotes" />
                        {{ __('shopper::forms.actions.send') }}
                    </x-filament::button>
                </div>
            @endif
        </div>
    </div>
</div>
