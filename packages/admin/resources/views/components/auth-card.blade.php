<div class="relative isolate flex min-h-screen items-center justify-center">
    <svg
        class="absolute inset-0 -z-10 hidden h-full w-full stroke-gray-200 [mask-image:radial-gradient(64rem_64rem_at_top,white,transparent)] dark:stroke-gray-900 sm:block"
        aria-hidden="true"
    >
        <defs>
            <pattern
                id="55d3d46d-692e-45f2-becd-d8bdc9344f45"
                width="200"
                height="200"
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
        class="absolute inset-x-0 top-10 -z-10 flex transform-gpu justify-center overflow-hidden blur-3xl"
        aria-hidden="true"
    >
        <div
            class="aspect-[1108/632] w-[69.25rem] flex-none bg-gradient-to-r from-primary-400 to-primary-600 opacity-20 dark:from-primary-200 dark:to-primary-500"
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
    <div class="relative w-full max-w-md">
        <div
            class="absolute inset-x-0 -top-6 max-w-md scale-90 transform rounded-xl bg-gradient-to-b from-gray-50/70 px-6 py-10 ring-1 ring-gray-200 backdrop-blur-sm dark:from-gray-800/60 dark:ring-gray-700/50"
        ></div>
        <div
            class="absolute inset-x-0 -top-3.5 max-w-md scale-95 transform rounded-xl bg-gradient-to-b from-gray-50/70 px-6 py-10 ring-1 ring-gray-200 backdrop-blur-sm dark:from-gray-800/60 dark:ring-gray-700/50"
        ></div>
        <div
            {{ $attributes->twMerge(['class' => 'relative w-full max-w-md overflow-hidden border-t border-gray-200 dark:border-gray-700/50 px-6 py-10 rounded-xl backdrop-blur-sm bg-gradient-to-b from-gray-50/70 dark:from-gray-800/60']) }}
        >
            {{ $slot }}
        </div>
    </div>
</div>
