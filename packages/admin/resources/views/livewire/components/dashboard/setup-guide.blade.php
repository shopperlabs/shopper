<div class="mx-auto w-full max-w-2xl">
    <div class="text-center">
        <h1 class="font-heading text-sh-fg text-3xl font-bold tracking-tight">
            {{ __('shopper::pages/dashboard.welcome_message') }}
        </h1>
        <p class="text-sh-fg-muted mt-2 text-base">
            {{ __('shopper::pages/dashboard.welcome_description') }}
        </p>
    </div>

    <div
        x-data="{
            expandedStep: @js(
                        collect($this->steps)->search(fn ($step) => ! $step['completed']) !== false
                            ? collect($this->steps)->search(fn ($step) => ! $step['completed'])
                            : null
                    ),
        }"
        x-init="$nextTick(() => $el.classList.add('opacity-100', 'translate-y-0'))"
        class="mt-10 translate-y-4 opacity-0 transition-all duration-500 ease-out"
    >
        <x-shopper::card class="[&>div:first-of-type]:p-0">
            <x-slot:title>
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div
                            class="bg-primary-50 dark:bg-primary-500/10 flex size-10 items-center justify-center rounded-xl"
                        >
                            <x-phosphor-rocket-duotone class="text-primary-500 size-5" aria-hidden="true" />
                        </div>
                        <div>
                            <h3 class="font-heading text-sh-fg text-base font-semibold">
                                {{ __('shopper::pages/dashboard.guide.title') }}
                            </h3>
                            <p class="text-sh-fg-muted mt-0.5 text-sm">
                                {{ __('shopper::pages/dashboard.guide.description') }}
                            </p>
                        </div>
                    </div>
                    <span class="text-sh-fg-muted text-sm tabular-nums">
                        <span class="text-sh-fg font-semibold">{{ $this->completedCount }}</span>
                        {{ __('shopper::pages/dashboard.guide.progress', ['total' => $this->totalSteps]) }}
                    </span>
                </div>

                <div class="bg-sh-muted mt-5 h-2 overflow-hidden rounded-full">
                    <div
                        class="bg-primary-500 h-full rounded-full transition-all duration-700 ease-out"
                        style="
                            width: {{ $this->totalSteps > 0 ? round(($this->completedCount / $this->totalSteps) * 100) : 0 }}%;
                        "
                    ></div>
                </div>
            </x-slot>

            <div>
                <div class="divide-sh-border divide-y">
                    @foreach ($this->steps as $index => $step)
                        @can($step['permission'])
                            <div>
                                <button
                                    type="button"
                                    class="group flex w-full items-center gap-4 px-6 py-4 text-left transition-colors duration-150"
                                    x-on:click="expandedStep = expandedStep === {{ $index }} ? null : {{ $index }}"
                                >
                                    @if ($step['completed'])
                                        <span
                                            class="flex size-8 shrink-0 items-center justify-center rounded-full bg-green-50 ring-1 ring-green-200 dark:bg-green-500/10 dark:ring-green-500/20"
                                        >
                                            <x-untitledui-check class="size-4 text-green-600 dark:text-green-400" />
                                        </span>
                                    @else
                                        <span
                                            class="bg-sh-muted ring-sh-border flex size-8 shrink-0 items-center justify-center rounded-full ring-1 transition-colors duration-150"
                                            x-bind:class="
                                                expandedStep === {{ $index }} &&
                                                    'bg-primary-50 ring-primary-200 dark:bg-primary-500/10 dark:ring-primary-500/20'
                                            "
                                        >
                                            @svg($step['icon'], 'text-sh-fg-muted size-4 transition-colors duration-150', ['x-bind:class' => "expandedStep === {$index} && 'text-primary-500 dark:text-primary-400'"])
                                        </span>
                                    @endif

                                    <span
                                        @class([
                                            'flex-1 text-sm font-medium transition-colors duration-150',
                                            'text-sh-fg-muted' => $step['completed'],
                                            'text-sh-fg-secondary group-hover:text-sh-fg' => ! $step['completed'],
                                        ])
                                    >
                                        <span
                                            @class(['line-through decoration-gray-300 dark:decoration-gray-600' => $step['completed']])
                                        >
                                            {{ __('shopper::pages/dashboard.guide.steps.' . $step['key'] . '.title') }}
                                        </span>
                                    </span>

                                    @if (! $step['completed'])
                                        <x-untitledui-chevron-down
                                            class="text-sh-fg-muted size-4 transition-transform duration-200 ease-out"
                                            x-bind:class="{ '-rotate-180': expandedStep === {{ $index }} }"
                                        />
                                    @endif
                                </button>

                                @if (! $step['completed'])
                                    <div x-show="expandedStep === {{ $index }}" x-collapse.duration.300ms>
                                        <div class="px-6 pb-5 pl-18">
                                            <p class="text-sh-fg-muted text-sm leading-relaxed">
                                                {{ __('shopper::pages/dashboard.guide.steps.' . $step['key'] . '.description') }}
                                            </p>
                                            <a
                                                href="{{ route($step['route']) }}"
                                                wire:navigate
                                                class="bg-primary-600 hover:bg-primary-700 focus-visible:ring-primary-600 mt-4 inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white shadow-sm transition-all duration-150 focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none dark:focus-visible:ring-offset-gray-900"
                                            >
                                                {{ __('shopper::pages/dashboard.guide.steps.' . $step['key'] . '.action') }}
                                                <x-untitledui-arrow-narrow-right class="size-4" />
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endcan
                    @endforeach
                </div>

                <div class="border-sh-border flex items-center justify-between border-t px-6 py-3">
                    <p class="text-sh-fg-muted text-sm/4">
                        {{ __('shopper::pages/dashboard.guide.footer_hint') }}
                    </p>
                    <button
                        type="button"
                        wire:click="complete"
                        class="text-sh-fg-muted hover:text-sh-fg text-xs font-medium transition-colors duration-150"
                    >
                        {{ __('shopper::pages/dashboard.guide.dismiss') }}
                    </button>
                </div>
            </div>
        </x-shopper::card>
    </div>

    <div class="mt-12">
        <h3 class="font-heading text-sh-fg text-lg font-medium">
            {{ __('shopper::pages/dashboard.addons.title') }}
        </h3>

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <a
                href="https://docs.laravelshopper.dev/v2/addons/stripe"
                target="_blank"
                class="group bg-sh-surface ring-sh-border hover:ring-sh-border relative overflow-hidden rounded-xl p-5 ring-1 transition-all duration-200 hover:shadow-xs"
            >
                <div class="flex items-center gap-4">
                    <img
                        src="{{ shopper_panel_assets('/images/payments/stripe.svg') }}"
                        alt="Stripe"
                        class="size-6 shrink-0 rounded-lg"
                    />
                    <div class="flex min-w-0 flex-1 items-center gap-2">
                        <h4 class="text-sh-fg text-sm font-semibold">
                            {{ __('shopper::pages/dashboard.addons.stripe.title') }}
                        </h4>
                        <x-filament::badge color="sky" size="sm">
                            {{ __('shopper::pages/dashboard.addons.badge') }}
                        </x-filament::badge>
                    </div>
                </div>
                <p class="text-sh-fg-muted mt-4 text-sm leading-relaxed">
                    {{ __('shopper::pages/dashboard.addons.stripe.description') }}
                </p>
                <div
                    class="text-sh-fg-secondary group-hover:text-sh-fg mt-2 inline-flex items-center gap-1.5 text-sm font-medium transition-colors duration-150"
                >
                    {{ __('shopper::pages/dashboard.addons.learn_more') }}
                    <x-untitledui-arrow-narrow-right
                        class="size-3.5 transition-transform duration-150 group-hover:translate-x-0.5"
                    />
                </div>
            </a>

            <x-shopper::link
                :href="route('shopper.settings.carriers')"
                wire:navigate
                class="group bg-sh-surface ring-sh-border hover:ring-sh-border relative overflow-hidden rounded-xl p-5 ring-1 transition-all duration-200 hover:shadow-xs"
            >
                <div class="flex items-center gap-4">
                    <div class="flex -space-x-1 overflow-hidden p-0.5">
                        <img
                            src="{{ shopper_panel_assets('/images/carriers/ups.svg') }}"
                            alt="UPS"
                            class="inline-block size-6 rounded-full ring-2 ring-white outline -outline-offset-1 outline-black/5 dark:outline-white/10"
                        />
                        <img
                            src="{{ shopper_panel_assets('/images/carriers/fedex.svg') }}"
                            alt="FedEx"
                            class="inline-block size-6 rounded-full ring-2 ring-white outline -outline-offset-1 outline-black/5 dark:outline-white/10"
                        />
                        <img
                            src="{{ shopper_panel_assets('/images/carriers/usps.svg') }}"
                            alt="USPS"
                            class="inline-block size-6 rounded-full ring-2 ring-white outline -outline-offset-1 outline-black/5 dark:outline-white/10"
                        />
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="text-sh-fg text-sm font-semibold">
                            {{ __('shopper::pages/dashboard.addons.carriers.title') }}
                        </h4>
                    </div>
                </div>
                <p class="text-sh-fg-muted mt-4 text-sm leading-relaxed">
                    {{ __('shopper::pages/dashboard.addons.carriers.description') }}
                </p>
                <div
                    class="text-sh-fg-secondary group-hover:text-sh-fg mt-2 inline-flex items-center gap-1.5 text-sm font-medium transition-colors duration-150"
                >
                    {{ __('shopper::pages/dashboard.addons.configure') }}
                    <x-untitledui-arrow-narrow-right
                        class="size-3.5 transition-transform duration-150 group-hover:translate-x-0.5"
                    />
                </div>
            </x-shopper::link>
        </div>
    </div>
</div>
