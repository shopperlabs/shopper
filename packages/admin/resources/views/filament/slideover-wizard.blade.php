@php
    $isContained = $isContained();
    $key = $getKey();
    $previousAction = $getAction("previous");
    $nextAction = $getAction("next");
    $steps = $getChildSchema()->getComponents();
@endphp

<div
    x-load
    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc("wizard", "filament/schemas") }}"
    x-data="wizardSchemaComponent({
                isSkippable: @js($isSkippable()),
                isStepPersistedInQueryString: @js($isStepPersistedInQueryString()),
                key: @js($key),
                startStep: @js($getStartStep()),
                stepQueryStringKey: @js($getStepQueryStringKey()),
            })"
    x-on:next-wizard-step.window="if ($event.detail.key === @js($key)) goToNextStep()"
    wire:ignore.self
    {{
        $attributes
            ->merge(
                [
                    "id" => $getId(),
                ],
                escape: false,
            )
            ->merge($getExtraAttributes(), escape: false)
            ->merge($getExtraAlpineAttributes(), escape: false)
            ->class([
                "fi-sc-wizard fi-sc-wizard-slideover relative flex h-full flex-1 flex-col",
                "fi-contained" => $isContained,
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

    <div class="sticky top-0 z-40 bg-sh-muted backdrop-blur-lg/75">
        <ol
            @if (filled($label = $getLabel()))
                aria-label="{{ $label }}"
            @endif
            role="list"
            class="fi-sc-wizard-header scrolling flex gap-3 overflow-x-auto px-4"
            x-cloak
            x-ref="header"
        >
            @foreach ($steps as $step)
                <li
                    class="fi-sc-wizard-header-step relative inline-flex items-center gap-4 truncate px-0.5 py-2"
                    x-bind:class="{
                        'fi-active': getStepIndex(step) === {{ $loop->index }},
                        'fi-completed': getStepIndex(step) > {{ $loop->index }},
                    }"
                >
                    <button
                        type="button"
                        x-bind:aria-current="getStepIndex(step) === {{ $loop->index }} ? 'step' : null"
                        x-on:click="step = @js($step->getKey())"
                        x-bind:disabled="! isStepAccessible(@js($step->getKey())) || @js($previousAction->isDisabled())"
                        role="step"
                        class="fi-sc-wizard-header-step-btn inline-flex items-center gap-2 truncate rounded-full bg-sh-surface py-1.5 pr-4 pl-2 text-start ring-1 ring-sh-border hover:bg-sh-muted"
                    >
                        <div
                            class="fi-sc-wizard-header-step-icon-ctn flex size-6 shrink-0 items-center justify-center rounded-full"
                            x-bind:class="{
                                'bg-primary-600 dark:bg-primary-500':
                                    getStepIndex(step) > {{ $loop->index }},
                                'border': getStepIndex(step) <= {{ $loop->index }},
                                'border-primary-600 dark:border-primary-500':
                                    getStepIndex(step) === {{ $loop->index }},
                                'border-sh-border':
                                    getStepIndex(step) < {{ $loop->index }},
                            }"
                        >
                            @php
                                $completedIcon = $step->getCompletedIcon();
                            @endphp

                            {{
                                \Filament\Support\generate_icon_html(
                                    $completedIcon ?? \Filament\Support\Icons\Heroicon::OutlinedCheck,
                                    alias: filled($completedIcon) ? null : \Filament\Schemas\View\SchemaIconAlias::COMPONENTS_WIZARD_COMPLETED_STEP,
                                    attributes: new \Illuminate\View\ComponentAttributeBag([
                                        "x-cloak" => "x-cloak",
                                        "x-show" => "getStepIndex(step) > {$loop->index}",
                                        "class" => "fi-sc-wizard-header-step-icon size-4 text-white",
                                    ]),
                                    size: \Filament\Support\Enums\IconSize::Small,
                                )
                            }}

                            @if (filled($icon = $step->getIcon()))
                                {{
                                    \Filament\Support\generate_icon_html(
                                        $icon,
                                        attributes: new \Illuminate\View\ComponentAttributeBag([
                                            "x-cloak" => "x-cloak",
                                            "x-show" => "getStepIndex(step) <= {$loop->index}",
                                            "x-bind:class" => "{
                                                                                                                        'text-sh-fg-muted': getStepIndex(step) !== {$loop->index},
                                                                                                                        'text-primary-600 dark:text-primary-500': getStepIndex(step) === {$loop->index},
                                                                                                                    }",
                                            "class" => "fi-sc-wizard-header-step-icon size-4",
                                        ]),
                                        size: \Filament\Support\Enums\IconSize::Small,
                                    )
                                }}
                            @else
                                <span
                                    x-show="getStepIndex(step) <= {{ $loop->index }}"
                                    class="fi-sc-wizard-header-step-number text-sm font-medium"
                                    x-bind:class="{
                                        'text-sh-fg-muted':
                                            getStepIndex(step) !== {{ $loop->index }},
                                        'text-primary-600 dark:text-primary-500':
                                            getStepIndex(step) === {{ $loop->index }},
                                    }"
                                >
                                    {{ str_pad($loop->index + 1, 2, "0", STR_PAD_LEFT) }}
                                </span>
                            @endif
                        </div>

                        <div class="grid justify-items-start md:w-max md:max-w-60">
                            @if (! $step->isLabelHidden())
                                <span
                                    class="fi-sc-wizard-header-step-label text-sm font-medium"
                                    x-bind:class="{
                                        'text-sh-fg-muted':
                                            getStepIndex(step) < {{ $loop->index }},
                                        'text-primary-600 dark:text-primary-400':
                                            getStepIndex(step) === {{ $loop->index }},
                                        'text-sh-fg': getStepIndex(step) > {{ $loop->index }},
                                    }"
                                >
                                    {{ $step->getLabel() }}
                                </span>
                            @endif
                        </div>
                    </button>

                    @if (! $loop->last)
                        <div aria-hidden="true">
                            <x-untitledui-chevron-right
                                class="size-5 text-sh-fg-muted rtl:rotate-180"
                                stroke-width="1.5"
                                aria-hidden="true"
                            />
                        </div>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>

    <div class="h-0 w-full flex-1 overflow-y-auto">
        @foreach ($steps as $step)
            {{ $step }}
        @endforeach
    </div>

    <div class="fi-sc-wizard-footer flex shrink-0 justify-end gap-3 border-t border-sh-border p-4">
        <div
            x-cloak
            @if (! $previousAction->isDisabled())
                x-on:click="goToPreviousStep"
            @endif
            x-show="! isFirstStep()"
        >
            {{ $previousAction }}
        </div>

        <div x-show="isFirstStep()">
            {{ $getCancelAction() }}
        </div>

        <div
            x-cloak
            @if (! $nextAction->isDisabled())
                x-on:click="requestNextStep()"
            @endif
            x-bind:class="{ 'fi-hidden': isLastStep() }"
            wire:loading.class="fi-disabled"
        >
            {{ $nextAction }}
        </div>

        <div x-bind:class="{ 'fi-hidden': ! isLastStep() }">
            {{ $getSubmitAction() }}
        </div>
    </div>
</div>
