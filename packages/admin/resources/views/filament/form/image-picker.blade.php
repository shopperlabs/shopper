@php
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
        }"
        class="grid grid-cols-3 gap-3 sm:grid-cols-4"
        role="radiogroup"
    >
        @foreach ($getOptions() as $value => $url)
            <label class="relative block {{ $isDisabled ? 'cursor-not-allowed opacity-60' : 'cursor-pointer' }}">
                <input
                    type="radio"
                    name="{{ $getId() }}"
                    value="{{ $value }}"
                    x-model="state"
                    @disabled($isDisabled)
                    class="peer sr-only"
                />

                <img
                    src="{{ $url }}"
                    alt="{{ basename(parse_url($url, PHP_URL_PATH) ?? '') }}"
                    loading="lazy"
                    class="aspect-square w-full rounded-xl bg-sh-muted object-cover ring-1 ring-sh-border transition peer-checked:ring-2 peer-checked:ring-primary-500 peer-focus-visible:ring-2 peer-focus-visible:ring-primary-500"
                />

                <span
                    class="absolute top-2 left-2 hidden size-6 items-center justify-center rounded-full bg-primary-500 text-white shadow-sm peer-checked:flex"
                >
                    <x-filament::icon icon="untitledui-check" class="size-3.5" />
                </span>
            </label>
        @endforeach
    </div>
</x-dynamic-component>
