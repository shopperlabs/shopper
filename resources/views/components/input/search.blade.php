@props([
    'label' => false,
    'for' => false,
    'placeholder' => '',
])

<div>
    @if($label)
        <label for="search" class="sr-only">{{ __($label) }}</label>
    @endif
    <div class="flex rounded-md shadow-sm">
        <div class="relative flex-grow focus-within:z-10">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                </svg>
            </div>
            <input id="search" {{ $attributes->wire('model') }} class="form-input block w-full rounded-none rounded-md pl-10 transition ease-in-out duration-150 sm:text-sm sm:leading-5" placeholder="{{ __($placeholder) }}" />
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <svg wire:loading {{ $attributes->wire('target') }} class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
            </div>
        </div>
    </div>
</div>
