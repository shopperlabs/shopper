<div>
    <x-filament::modal
        id="session-expired"
        :close-by-clicking-away="false"
        :close-by-escaping="false"
        width="md"
    >
        <x-slot name="heading">
            {{ __('shopper::pages/auth.session_expired.title') }}
        </x-slot>

        <x-slot name="description">
            {{ __('shopper::pages/auth.session_expired.description') }}
        </x-slot>

        <form wire:submit="attempt" class="flex items-center gap-2">
            <div class="flex-1">
                {{ $this->form }}
            </div>

            <x-filament::button type="submit" wire:loading.attr="disabled" wire:target="attempt">
                <span wire:loading.remove wire:target="attempt">
                    {{ __('shopper::pages/auth.session_expired.submit') }}
                </span>
                <span wire:loading wire:target="attempt">
                    {{ __('shopper::pages/auth.session_expired.submitting') }}
                </span>
            </x-filament::button>
        </form>
    </x-filament::modal>
</div>

@script
    <script>
        Livewire.interceptRequest(({ onError }) => {
            onError(({ response, preventDefault }) => {
                if (response.status === 419) {
                    preventDefault()
                    Livewire.dispatch('open-modal', { id: 'session-expired' })
                }
            })
        })
    </script>
@endscript
