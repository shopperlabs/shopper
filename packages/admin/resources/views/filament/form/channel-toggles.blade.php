@php
    use Filament\Support\View\Components\ToggleComponent;
    use Illuminate\Support\Arr;

    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $channels = $getChannels();
    $counterTemplate = e(__('shopper::pages/products.channels_counter', ['count' => '{COUNT}', 'total' => $channels->count()]));
    $onClasses = Arr::toCssClasses(['fi-toggle-on', ...\Filament\Support\get_component_color_classes(ToggleComponent::class, 'success')]);
    $offClasses = Arr::toCssClasses(['fi-toggle-off', ...\Filament\Support\get_component_color_classes(ToggleComponent::class, 'gray')]);
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')") }},
            isChecked(id) {
                return (this.state ?? []).includes(id)
            },
            toggle(id) {
                this.state = this.isChecked(id)
                    ? this.state.filter((value) => value !== id)
                    : [...(this.state ?? []), id]
            },
        }"
    >
        <div class="divide-y divide-sh-border">
            @foreach ($channels as $channel)
                @php
                    $optionDisabled = $isDisabled || $isOptionDisabled($channel->id, $channel->name);
                    $logo = \Shopper\Core\Channel\Facades\Channels::logoFor($channel->driver);
                @endphp

                <div class="flex items-center justify-between gap-4 py-3 first:pt-0 last:pb-0 {{ $optionDisabled ? 'opacity-60' : '' }}">
                    <span class="flex min-w-0 items-center gap-3">
                        @if ($logo)
                            <img src="{{ $logo }}" alt="" class="size-8 shrink-0 rounded-lg object-cover" />
                        @else
                            <span class="bg-sh-muted flex size-8 shrink-0 items-center justify-center rounded-lg">
                                <x-untitledui-share-07 class="text-sh-fg-muted size-4" aria-hidden="true" />
                            </span>
                        @endif

                        <span class="text-sh-fg truncate text-sm font-medium">{{ $channel->name }}</span>

                        @unless ($channel->is_enabled)
                            <x-filament::badge size="sm" color="gray">
                                {{ __('shopper::words.is_disabled') }}
                            </x-filament::badge>
                        @endunless
                    </span>

                    <button
                        type="button"
                        role="switch"
                        aria-label="{{ $channel->name }}"
                        x-bind:aria-checked="isChecked('{{ $channel->id }}').toString()"
                        x-on:click="toggle('{{ $channel->id }}')"
                        x-bind:class="isChecked('{{ $channel->id }}') ? @js($onClasses) : @js($offClasses)"
                        class="fi-toggle"
                        @disabled($optionDisabled)
                    >
                        <div>
                            <div aria-hidden="true"></div>

                            <div aria-hidden="true"></div>
                        </div>
                    </button>
                </div>
            @endforeach
        </div>

        <p class="text-sh-fg-muted mt-3 text-sm">
            {!! str_replace('{COUNT}', '<span x-text="(state ?? []).length"></span>', $counterTemplate) !!}
        </p>
    </div>
</x-dynamic-component>
