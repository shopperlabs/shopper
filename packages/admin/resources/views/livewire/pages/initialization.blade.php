<div class="min-h-screen lg:grid lg:grid-cols-5 lg:gap-x-20">
    <main class="mx-auto max-w-4xl px-4 py-12 sm:px-6 sm:py-20 lg:max-w-xl lg:col-span-3">
        <livewire:shopper-initialize-wizard />
    </main>
    <div class="min-h-full hidden px-1 pt-1.5 lg:flex lg:col-span-2">
        <div class="relative pt-32 pl-32 flex flex-1 overflow-hidden bg-gray-50 rounded-tr-2xl rounded-br-2xl dark:bg-gray-800">
            <svg class="absolute inset-0 z-10 h-full w-full stroke-gray-200 dark:stroke-gray-900 [mask-image:radial-gradient(64rem_64rem_at_top,white,transparent)]" aria-hidden="true">
                <defs>
                    <pattern id="55d3d46d-692e-45f2-becd-d8bdc9344f45" width="250" height="250" x="50%" y="0" patternUnits="userSpaceOnUse">
                        <path d="M.5 200V.5H200" fill="none" />
                    </pattern>
                </defs>
                <svg x="50%" y="0" class="overflow-visible fill-gray-50 dark:fill-gray-900">
                    <path d="M-200.5 0h201v201h-201Z M599.5 0h201v201h-201Z M399.5 400h201v201h-201Z M-400.5 600h201v201h-201Z" stroke-width="0" />
                </svg>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#55d3d46d-692e-45f2-becd-d8bdc9344f45)" />
            </svg>
            <div class="absolute inset-x-0 top-10 z-10 flex transform-gpu justify-center overflow-hidden blur-3xl" aria-hidden="true">
                <div
                    class="aspect-[1108/632] w-[69.25rem] flex-none bg-gradient-to-r from-primary-400 to-primary-600 dark:from-primary-200 dark:to-primary-500 opacity-20"
                    style="clip-path: polygon(73.6% 51.7%, 91.7% 11.8%, 100% 46.4%, 97.4% 82.2%, 92.5% 84.9%, 75.7% 64%, 55.3% 47.5%, 46.5% 49.4%, 45% 62.9%, 50.3% 87.2%, 21.3% 64.1%, 0.1% 100%, 5.4% 51.1%, 21.4% 63.9%, 58.9% 0.2%, 73.6% 51.7%)"
                ></div>
            </div>
            <div class="relative flex rounded-tl-2xl shadow-xl h-full w-full z-20 bg-white dark:bg-gray-950">
                <div class="p-8">
                    <x-shopper::brand class="h-10 w-auto" />
                </div>
                <div class="border-l border-gray-100 pl-4 flex-1 divide-y divide-gray-100 dark:divide-gray-700 dark:border-gray-700">
                    <div class="py-8 px-5">
                        <h2 class="text-xl font-heading font-bold text-gray-900 dark:text-white">
                            {{ shopper_setting(key: 'name', withCatch:  false) ?? config('app.name') }}
                        </h2>
                    </div>
                    <div class="py-8 px-5 space-y-16">
                        <div class="flex items-center space-x-3">
                            <x-untitledui-home-line
                                class="h-6 w-6 text-gray-400"
                                stroke-width="1.5"
                                aria-hidden="true"
                            />
                            <span class="h-3.5 w-1/2 bg-gray-100 rounded-full animate-pulse dark:bg-gray-800"></span>
                        </div>
                        <div>
                            <h5 class="text-xs leading-5 text-gray-500 dark:text-gray-400 uppercase tracking-wider font-medium">
                                {{ __('shopper::layout.sidebar.catalog') }}
                            </h5>
                            <ul class="mt-5 space-y-8">
                                @foreach(range(0, 8) as $value)
                                    <li class="flex items-center space-x-2 animate-pulse">
                                        <div class="h-3 w-3 bg-gray-100 rounded-full dark:bg-gray-800"></div>
                                        <span class="h-3 w-1/2 bg-gray-100 rounded-full dark:bg-gray-800"
                                            style="width: {{ collect(range(1, 8))->shuffle()->first() * 10 }}%"></span>
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
