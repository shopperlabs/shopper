@php
    $key = $getKey();
    $previousAction = $getAction('previous');
    $nextAction = $getAction('next');
    $steps = $getChildSchema()->getComponents();
@endphp

<div
    x-load
    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('wizard', 'filament/schemas') }}"
    x-data="wizardSchemaComponent({
                isSkippable: @js($isSkippable()),
                isStepPersistedInQueryString: @js($isStepPersistedInQueryString()),
                key: @js($key),
                startStep: @js($getStartStep()),
                stepQueryStringKey: @js($getStepQueryStringKey()),
            })"
    x-on:next-wizard-step.window="if ($event.detail.key === @js($key)) goToNextStep()"
    x-on:go-to-wizard-step.window="$event.detail.key === @js($key) && goToStep($event.detail.step)"
    wire:ignore.self
    {{
        $attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->merge($getExtraAlpineAttributes(), escape: false)
            ->class([
                'fi-sc-wizard fi-sc-wizard-onboarding flex flex-col space-y-10',
            ])
    }}
>
    <input
        type="hidden"
        value="{{
            collect($steps)
                ->filter(static fn (\Filament\Schemas\Components\Wizard\Step $step): bool => $step->isVisible())
                ->map(static fn (\Filament\Schemas\Components\Wizard\Step $step): ?string => $step->getKey())
                ->values()
                ->toJson()
        }}"
        x-ref="stepsData"
    />

    <ol
        @if (filled($label = $getLabel()))
            aria-label="{{ $label }}"
        @endif
        role="list"
        class="flex items-center space-x-4"
        x-cloak
        x-ref="header"
    >
        @foreach ($steps as $step)
            <li
                class="inline-flex items-center text-sm leading-6"
                x-bind:class="{
                    'cursor-pointer': getStepIndex(step) > {{ $loop->index }},
                }"
            >
                <button
                    type="button"
                    class="inline-flex items-center"
                    x-bind:aria-current="getStepIndex(step) === {{ $loop->index }} ? 'step' : null"
                    x-on:click="step = @js($step->getKey())"
                    x-bind:disabled="! isStepAccessible(@js($step->getKey())) || @js($previousAction->isDisabled())"
                >
                    <span
                        class="relative flex size-6 items-center justify-center rounded-full text-xs leading-5"
                        x-bind:class="{
                            'bg-primary-600': getStepIndex(step) > {{ $loop->index }},
                            'border border-gray-300 bg-white text-gray-500 dark:border-white/10 dark:bg-gray-950 dark:text-gray-400':
                                getStepIndex(step) <= {{ $loop->index }},
                        }"
                    >
                        <x-untitledui-check-circle
                            x-cloak
                            x-show="getStepIndex(step) > {{ $loop->index }}"
                            class="size-5 text-white"
                            stroke-width="1.5"
                            aria-hidden="true"
                        />

                        <span
                            x-cloak
                            x-show="getStepIndex(step) <= {{ $loop->index }}"
                        >
                            {{ $loop->index + 1 }}
                        </span>
                    </span>

                    @if (! $step->isLabelHidden())
                        <span
                            class="ml-2 text-sm leading-6"
                            x-bind:class="{
                                'text-gray-900 dark:text-white': getStepIndex(step) > {{ $loop->index }},
                                'font-medium text-gray-900 dark:text-white':
                                    getStepIndex(step) === {{ $loop->index }},
                                'text-gray-500 dark:text-gray-400': getStepIndex(step) < {{ $loop->index }},
                            }"
                        >
                            {{ $step->getLabel() }}
                        </span>
                    @endif
                </button>

                @if (! $loop->last)
                    <div class="ml-5" aria-hidden="true">
                        <x-untitledui-chevron-right
                            class="size-5 text-gray-400 dark:text-gray-300"
                            stroke-width="1.5"
                            aria-hidden="true"
                        />
                    </div>
                @endif
            </li>
        @endforeach
    </ol>

    <div class="flex-1">
        @foreach ($steps as $step)
            {{ $step }}
        @endforeach
    </div>

    <div class="mt-8 border-t border-dashed border-gray-200 pt-10 dark:border-white/10">
        <div class="flex items-center justify-between space-x-4">
            <div
                x-cloak
                x-show="! isFirstStep()"
                @if (! $previousAction->isDisabled())
                    x-on:click="goToPreviousStep"
                @endif
            >
                {{ $previousAction }}
            </div>

            <div x-show="isFirstStep()">
                <span></span>
            </div>

            <div
                x-cloak
                x-show="! isLastStep()"
                @if (! $nextAction->isDisabled())
                    x-on:click="requestNextStep()"
                @endif
                wire:loading.class="pointer-events-none opacity-70"
            >
                {{ $nextAction }}
            </div>

            <div x-cloak x-show="isLastStep()">
                {{ $getSubmitAction() }}
            </div>
        </div>
    </div>
</div>
