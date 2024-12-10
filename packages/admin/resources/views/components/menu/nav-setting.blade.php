@props([
    "menu",
])

<a
    href="{{ $menu["route"] ? route($menu["route"]) : "#" }}"
    @class([
        "select-none whitespace-nowrap border-b-[3px] px-1 py-4 text-sm font-medium",
        "current border-primary-500 text-primary-600 dark:text-primary-500" =>
            $menu["route"] && request()->routeIs($menu["route"] . "*"),
        "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 dark:text-gray-400 dark:hover:border-gray-700 dark:hover:text-gray-300" =>
            ! $menu["route"] || ! request()->routeIs($menu["route"] . "*"),
    ])
    @if ($menu["route"] && request()->routeIs($menu["route"] . "*"))
        aria-current="page"
    @endif
    wire:navigate
>
    {{ __($menu["name"]) }}

    @if (! $menu["route"])
        <span
            class="ml-2 inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-700 dark:text-gray-300"
        >
            <svg
                class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-400 dark:text-gray-500"
                fill="currentColor"
                viewBox="0 0 8 8"
                aria-hidden="true"
            >
                <circle cx="4" cy="4" r="3" />
            </svg>
            {{ __("shopper::layout.soon") }}
        </span>
    @endif
</a>
