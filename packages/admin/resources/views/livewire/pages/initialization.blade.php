<div class="min-h-screen lg:grid lg:grid-cols-5 lg:gap-x-20">
    <main class="mx-auto px-4 py-12 sm:px-6 lg:col-span-3 lg:max-w-xl">
        <livewire:shopper-initialize-wizard />
    </main>
    <div class="hidden min-h-full p-1.5 lg:col-span-2 lg:flex">
        <div
            class="relative flex flex-1 overflow-hidden rounded-2xl bg-sh-muted pt-32 pl-32 ring-1 ring-sh-border"
        >
            <svg
                class="absolute inset-0 z-10 h-full w-full mask-[radial-gradient(64rem_64rem_at_top,white,transparent)] stroke-gray-200 dark:stroke-gray-900"
                aria-hidden="true"
            >
                <defs>
                    <pattern
                        id="55d3d46d-692e-45f2-becd-d8bdc9344f45"
                        width="250"
                        height="250"
                        x="50%"
                        y="0"
                        patternUnits="userSpaceOnUse"
                    >
                        <path d="M.5 200V.5H200" fill="none" />
                    </pattern>
                </defs>
                <svg x="50%" y="0" class="overflow-visible fill-gray-50 dark:fill-gray-900">
                    <path
                        d="M-200.5 0h201v201h-201Z M599.5 0h201v201h-201Z M399.5 400h201v201h-201Z M-400.5 600h201v201h-201Z"
                        stroke-width="0"
                    />
                </svg>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#55d3d46d-692e-45f2-becd-d8bdc9344f45)" />
            </svg>
            <div
                class="absolute inset-x-0 top-10 z-10 flex transform-gpu justify-center overflow-hidden blur-3xl"
                aria-hidden="true"
            >
                <div
                    class="from-primary-400 to-primary-600 dark:from-primary-200 dark:to-primary-500 aspect-[1108/632] w-[69.25rem] flex-none bg-gradient-to-r opacity-20"
                    style="
                        clip-path: polygon(
                            73.6% 51.7%,
                            91.7% 11.8%,
                            100% 46.4%,
                            97.4% 82.2%,
                            92.5% 84.9%,
                            75.7% 64%,
                            55.3% 47.5%,
                            46.5% 49.4%,
                            45% 62.9%,
                            50.3% 87.2%,
                            21.3% 64.1%,
                            0.1% 100%,
                            5.4% 51.1%,
                            21.4% 63.9%,
                            58.9% 0.2%,
                            73.6% 51.7%
                        );
                    "
                ></div>
            </div>
            <div class="relative z-20 flex h-full w-full rounded-tl-2xl bg-sh-surface shadow-xl">
                <div class="p-8">
                    <x-shopper::brand class="size-10" aria-hidden="true" />
                </div>
                <div
                    class="flex-1 divide-y divide-sh-border border-l border-sh-border pl-4"
                >
                    <div class="px-5 py-8">
                        <h2 class="font-heading text-xl font-bold text-sh-fg">
                            {{ shopper_setting(key: 'name', withCache: false) ?? config('app.name') }}
                        </h2>
                    </div>
                    <div class="space-y-16 px-5 py-8">
                        <div class="flex items-center space-x-3">
                            <x-untitledui-home-line
                                class="size-6 text-sh-fg-muted"
                                stroke-width="1.5"
                                aria-hidden="true"
                            />
                            <span class="h-3.5 w-1/2 animate-pulse rounded-full bg-sh-skeleton"></span>
                        </div>
                        <div>
                            <h5
                                class="text-xs leading-5 font-medium tracking-wider text-sh-fg-muted uppercase"
                            >
                                {{ __('shopper::layout.sidebar.catalog') }}
                            </h5>
                            <ul class="mt-5 space-y-8">
                                @foreach (range(0, 8) as $value)
                                    <li class="flex animate-pulse items-center space-x-2">
                                        <div class="h-3 w-3 rounded-full bg-sh-skeleton"></div>
                                        <span
                                            class="h-3 w-1/2 rounded-full bg-sh-skeleton"
                                            style="width: {{ collect(range(1, 8))->shuffle()->first() * 10 }}%"
                                        ></span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
