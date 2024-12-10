@php
    $isContained = $isContained();
    $statePath = $getStatePath();
    $previousAction = $getAction('previous');
    $nextAction = $getAction('next');
@endphp

<div
    wire:ignore.self
    x-cloak
    x-data="{
        step: null,

        nextStep: function () {
            let nextStepIndex = this.getStepIndex(this.step) + 1

            if (nextStepIndex >= this.getSteps().length) {
                return
            }

            this.step = this.getSteps()[nextStepIndex]

            this.autofocusFields()
            this.scroll()
        },

        previousStep: function () {
            let previousStepIndex = this.getStepIndex(this.step) - 1

            if (previousStepIndex < 0) {
                return
            }

            this.step = this.getSteps()[previousStepIndex]

            this.autofocusFields()
            this.scroll()
        },

        scroll: function () {
            this.$nextTick(() => {
                this.$refs.header.children[
                    this.getStepIndex(this.step)
                ].scrollIntoView({ behavior: 'smooth', block: 'start' })
            })
        },

        autofocusFields: function () {
            $nextTick(() =>
                this.$refs[`step-${this.step}`]
                    .querySelector('[autofocus]')
                    ?.focus(),
            )
        },

        getStepIndex: function (step) {
            let index = this.getSteps().findIndex(
                (indexedStep) => indexedStep === step,
            )

            if (index === -1) {
                return 0
            }

            return index
        },

        getSteps: function () {
            return JSON.parse(this.$refs.stepsData.value)
        },

        isFirstStep: function () {
            return this.getStepIndex(this.step) <= 0
        },

        isLastStep: function () {
            return this.getStepIndex(this.step) + 1 >= this.getSteps().length
        },

        isStepAccessible: function (stepId) {
            return (
                @js($isSkippable()) || this.getStepIndex(this.step) > this.getStepIndex(stepId)
            )
        },

        updateQueryString: function () {
            if (! @js($isStepPersistedInQueryString())) {
                return
            }

            const url = new URL(window.location.href)
            url.searchParams.set(@js($getStepQueryStringKey()), this.step)

            history.pushState(null, document.title, url.toString())
        },
    }"
    x-init="
        $watch('step', () => updateQueryString())

        step = getSteps().at({{ $getStartStep() - 1 }})

        autofocusFields()
    "
    x-on:next-wizard-step.window="if ($event.detail.statePath === '{{ $statePath }}') nextStep()"
    {{
        $attributes
            ->merge([
                'id' => $getId()
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->merge($getExtraAlpineAttributes(), escape: false)
            ->class(['fi-fo-wizard-slideover relative h-full flex-1 flex flex-col', 'fi-contained' => $isContained])
    }}
>
    <input
        type="hidden"
        value="{{
            collect($getChildComponentContainer()->getComponents())
                ->filter(static fn (\Shopper\Components\Wizard\StepColumn $step): bool => $step->isVisible())
                ->map(static fn (\Shopper\Components\Wizard\StepColumn $step) => $step->getId())
                ->values()
                ->toJson()
        }}"
        x-ref="stepsData"
    />

    <div class="sticky top-0 z-40 bg-gray-50/80 dark:bg-gray-950/75 border-b border-gray-100 dark:border-white/10 backdrop-blur-md px-4">
        <ol
            @if (filled($label = $getLabel()))
                aria-label="{{ $label }}"
            @endif
            role="list"
            @class([
                'fi-fo-wizard-header scrolling overflow-x-auto',
            ])
            x-ref="header"
        >
            @foreach ($getChildComponentContainer()->getComponents() as $step)
                <li
                    class="fi-fo-wizard-header-step relative inline-flex items-center gap-4 py-2 px-0.5 truncate"
                    x-bind:class="{
                        'fi-active': getStepIndex(step) === {{ $loop->index }},
                        'fi-completed': getStepIndex(step) > {{ $loop->index }},
                    }"
                >
                    <button
                        type="button"
                        x-bind:aria-current="getStepIndex(step) === {{ $loop->index }} ? 'step' : null"
                        x-on:click="step = @js($step->getId())"
                        x-bind:disabled="! isStepAccessible(@js($step->getId()))"
                        role="step"
                        class="fi-fo-wizard-header-step-button inline-flex items-center bg-white dark:bg-gray-900 hover:bg-gray-50/20 dark:hover:bg-gray-900/50 rounded-full ring-1 ring-gray-200 dark:ring-white/10 gap-2 py-1.5 pl-2 pr-4 text-start truncate"
                    >
                        <div
                            class="fi-fo-wizard-header-step-icon-ctn flex size-6 shrink-0 items-center justify-center rounded-full"
                            x-bind:class="{
                                'bg-primary-600 dark:bg-primary-500':
                                    getStepIndex(step) > {{ $loop->index }},
                                'border': getStepIndex(step) <= {{ $loop->index }},
                                'border-primary-600 dark:border-primary-500':
                                    getStepIndex(step) === {{ $loop->index }},
                                'border-gray-300 dark:border-gray-600':
                                    getStepIndex(step) < {{ $loop->index }},
                            }"
                        >
                            <x-filament::icon
                                alias="forms::components.wizard.completed-step"
                                icon="heroicon-o-check"
                                x-cloak="x-cloak"
                                x-show="getStepIndex(step) > {{ $loop->index }}"
                                class="fi-fo-wizard-header-step-icon size-4 text-white"
                            />

                            @if (filled($icon = $step->getIcon()))
                                <x-filament::icon
                                    :icon="$icon"
                                    stroke-width="1.5"
                                    x-cloak="x-cloak"
                                    x-show="getStepIndex(step) <= {{ $loop->index }}"
                                    class="fi-fo-wizard-header-step-icon size-4"
                                    x-bind:class="{
                                        'text-gray-500 dark:text-gray-400': getStepIndex(step) !== {{ $loop->index }},
                                        'text-primary-600 dark:text-primary-500': getStepIndex(step) === {{ $loop->index }},
                                    }"
                                />
                            @else
                                <span
                                    x-show="getStepIndex(step) <= {{ $loop->index }}"
                                    class="fi-fo-wizard-header-step-indicator text-sm font-medium"
                                    x-bind:class="{
                                        'text-gray-500 dark:text-gray-400':
                                            getStepIndex(step) !== {{ $loop->index }},
                                        'text-primary-600 dark:text-primary-500':
                                            getStepIndex(step) === {{ $loop->index }},
                                    }"
                                >
                                    {{ str_pad($loop->index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            @endif
                        </div>

                        <div class="grid justify-items-start md:w-max md:max-w-60">
                            @if (! $step->isLabelHidden())
                                <span
                                    class="fi-fo-wizard-header-step-label text-sm font-medium"
                                    x-bind:class="{
                                        'text-gray-500 dark:text-gray-400':
                                            getStepIndex(step) < {{ $loop->index }},
                                        'text-primary-600 dark:text-primary-400':
                                            getStepIndex(step) === {{ $loop->index }},
                                        'text-gray-950 dark:text-white': getStepIndex(step) > {{ $loop->index }},
                                    }"
                                >
                                    {{ $step->getLabel() }}
                                </span>
                            @endif
                        </div>
                    </button>

                    @if (! $loop->last)
                        <div
                            aria-hidden="true"
                            class="fi-fo-wizard-header-step-separator"
                        >
                            <x-untitledui-chevron-right
                                class="size-5 text-gray-400 dark:text-gray-500 rtl:rotate-180"
                                stroke-width="1.5"
                                aria-hidden="true"
                            />
                        </div>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>

    <div class="w-full h-0 flex-1 overflow-y-auto">
        @foreach ($getChildComponentContainer()->getComponents() as $step)
            {{ $step }}
        @endforeach
    </div>

    <div class="flex shrink-0 justify-end gap-3 p-4 border-t border-gray-100 dark:border-white/10">
        <span
            x-cloak
            @if (! $previousAction->isDisabled())
                x-on:click="previousStep"
            @endif
            x-show="! isFirstStep()"
        >
            {{ $previousAction }}
        </span>

        <span x-show="isFirstStep()">
            {{ $getCancelAction() }}
        </span>

        <span
            x-cloak
            @if (! $nextAction->isDisabled())
                x-on:click="
                    $wire.dispatchFormEvent(
                        'wizard::nextStep',
                        '{{ $statePath }}',
                        getStepIndex(step),
                    )
                "
            @endif
            x-show="! isLastStep()"
        >
            {{ $nextAction }}
        </span>

        <span x-show="isLastStep()">
            {{ $getSubmitAction() }}
        </span>
    </div>
</div>
