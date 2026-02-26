<div class="w-full max-w-2xl mx-auto">
    <div class="text-center">
        <h1 class="font-heading text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
            {{ __('shopper::pages/dashboard.welcome_message') }}
        </h1>
        <p class="mt-2 text-base text-gray-500 dark:text-gray-400">
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
        class="mt-10 opacity-0 translate-y-4 transition-all duration-500 ease-out"
    >
        <x-shopper::card class="[&>div:first-of-type]:p-0">
            <x-slot:title>
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-primary-50 dark:bg-primary-500/10 flex size-10 items-center justify-center rounded-xl">
                            <x-phosphor-rocket-duotone class="text-primary-500 size-5" aria-hidden="true" />
                        </div>
                        <div>
                            <h3 class="font-heading text-base font-semibold text-gray-900 dark:text-white">
                                {{ __('shopper::pages/dashboard.guide.title') }}
                            </h3>
                            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                                {{ __('shopper::pages/dashboard.guide.description') }}
                            </p>
                        </div>
                    </div>
                    <span class="text-sm tabular-nums text-gray-500 dark:text-gray-400">
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $this->completedCount }}</span>
                        {{ __('shopper::pages/dashboard.guide.progress', ['total' => $this->totalSteps]) }}
                    </span>
                </div>

                <div class="mt-5 h-2 overflow-hidden rounded-full bg-gray-200 dark:bg-white/10">
                    <div
                        class="bg-primary-500 h-full rounded-full transition-all duration-700 ease-out"
                        style="width: {{ $this->totalSteps > 0 ? round(($this->completedCount / $this->totalSteps) * 100) : 0 }}%"
                    ></div>
                </div>
            </x-slot:title>

            <div>
                <div class="divide-y divide-gray-100 dark:divide-white/5">
                    @foreach ($this->steps as $index => $step)
                        @can($step['permission'])
                            <div>
                                <button
                                    type="button"
                                    class="group flex w-full items-center gap-4 px-6 py-4 text-left transition-colors duration-150"
                                    x-on:click="expandedStep = expandedStep === {{ $index }} ? null : {{ $index }}"
                                >
                                    @if ($step['completed'])
                                        <span class="flex size-8 shrink-0 items-center justify-center rounded-full bg-green-50 ring-1 ring-green-200 dark:bg-green-500/10 dark:ring-green-500/20">
                                            <x-untitledui-check class="size-4 text-green-600 dark:text-green-400" />
                                        </span>
                                    @else
                                        <span
                                            class="flex size-8 shrink-0 items-center justify-center rounded-full bg-gray-50 ring-1 ring-gray-200 transition-colors duration-150 dark:bg-white/5 dark:ring-white/10"
                                            x-bind:class="expandedStep === {{ $index }} && 'bg-primary-50 ring-primary-200 dark:bg-primary-500/10 dark:ring-primary-500/20'"
                                        >
                                            @svg($step['icon'], 'size-4 text-gray-400 dark:text-gray-500 transition-colors duration-150', ['x-bind:class' => "expandedStep === {$index} && 'text-primary-500 dark:text-primary-400'"])
                                        </span>
                                    @endif

                                    <span @class([
                                    'flex-1 text-sm font-medium transition-colors duration-150',
                                    'text-gray-400 dark:text-gray-600' => $step['completed'],
                                    'text-gray-700 group-hover:text-gray-900 dark:text-gray-300 dark:group-hover:text-white' => ! $step['completed'],
                                ])>
                                    <span @class(['line-through decoration-gray-300 dark:decoration-gray-600' => $step['completed']])>
                                        {{ __('shopper::pages/dashboard.guide.steps.' . $step['key'] . '.title') }}
                                    </span>
                                </span>

                                    @if (! $step['completed'])
                                        <x-untitledui-chevron-down
                                            class="size-4 text-gray-400 transition-transform duration-200 ease-out dark:text-gray-500"
                                            x-bind:class="{ '-rotate-180': expandedStep === {{ $index }} }"
                                        />
                                    @endif
                                </button>

                                @if (! $step['completed'])
                                    <div
                                        x-show="expandedStep === {{ $index }}"
                                        x-collapse.duration.300ms
                                    >
                                        <div class="px-6 pb-5 pl-18">
                                            <p class="text-sm leading-relaxed text-gray-500 dark:text-gray-400">
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

                <div class="flex items-center justify-between border-t border-gray-200 px-6 py-3 dark:border-white/10">
                    <p class="text-sm/4 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/dashboard.guide.footer_hint') }}
                    </p>
                    <button
                        type="button"
                        wire:click="complete"
                        class="text-xs font-medium text-gray-500 transition-colors duration-150 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300"
                    >
                        {{ __('shopper::pages/dashboard.guide.dismiss') }}
                    </button>
                </div>
            </div>
        </x-shopper::card>
    </div>

    <div class="mt-12">
        <h3 class="text-lg font-heading font-medium text-gray-900 dark:text-white">
            {{ __('shopper::pages/dashboard.addons.title') }}
        </h3>

        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <a
                href="https://docs.laravelshopper.dev/v2/addons/stripe"
                target="_blank"
                class="group relative overflow-hidden rounded-xl bg-white p-5 ring-1 ring-gray-200 transition-all duration-200 hover:shadow-xs hover:ring-gray-300 dark:bg-gray-900 dark:ring-white/10 dark:hover:ring-white/20"
            >
                <div class="flex items-center gap-4">
                    <img
                        src="{{ shopper_panel_assets('/images/payments/stripe.svg') }}"
                        alt="Stripe"
                        class="size-6 shrink-0 rounded-lg"
                    />
                    <div class="min-w-0 flex-1 flex items-center gap-2">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ __('shopper::pages/dashboard.addons.stripe.title') }}
                        </h4>
                        <x-filament::badge color="sky" size="sm">
                            {{ __('shopper::pages/dashboard.addons.badge') }}
                        </x-filament::badge>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-relaxed text-gray-500 dark:text-gray-400">
                    {{ __('shopper::pages/dashboard.addons.stripe.description') }}
                </p>
                <div class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 transition-colors duration-150 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">
                    {{ __('shopper::pages/dashboard.addons.learn_more') }}
                    <x-untitledui-arrow-narrow-right class="size-3.5 transition-transform duration-150 group-hover:translate-x-0.5" />
                </div>
            </a>

            <x-shopper::link
                :href="route('shopper.settings.carriers')"
                wire:navigate
                class="group relative overflow-hidden rounded-xl bg-white p-5 ring-1 ring-gray-200 transition-all duration-200 hover:shadow-xs hover:ring-gray-300 dark:bg-gray-900 dark:ring-white/10 dark:hover:ring-white/20"
            >
                <div class="flex items-center gap-4">
                    <div class="flex p-0.5 -space-x-1 overflow-hidden">
                        <img src="{{ shopper_panel_assets('/images/carriers/ups.svg') }}" alt="UPS" class="inline-block size-6 rounded-full ring-2 ring-white outline -outline-offset-1 outline-black/5 dark:ring-gray-900 dark:outline-white/10" />
                        <img src="{{ shopper_panel_assets('/images/carriers/fedex.svg') }}" alt="FedEx" class="inline-block size-6 rounded-full ring-2 ring-white outline -outline-offset-1 outline-black/5 dark:ring-gray-900 dark:outline-white/10" />
                        <img src="{{ shopper_panel_assets('/images/carriers/usps.svg') }}" alt="USPS" class="inline-block size-6 rounded-full ring-2 ring-white outline -outline-offset-1 outline-black/5 dark:ring-gray-900 dark:outline-white/10" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ __('shopper::pages/dashboard.addons.carriers.title') }}
                        </h4>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-relaxed text-gray-500 dark:text-gray-400">
                    {{ __('shopper::pages/dashboard.addons.carriers.description') }}
                </p>
                <div class="mt-2 inline-flex items-center gap-1.5 text-sm font-medium text-gray-600 transition-colors duration-150 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white">
                    {{ __('shopper::pages/dashboard.addons.configure') }}
                    <x-untitledui-arrow-narrow-right class="size-3.5 transition-transform duration-150 group-hover:translate-x-0.5" />
                </div>
            </x-shopper::link>
        </div>
    </div>
</div>
