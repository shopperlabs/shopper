<x-shopper::empty-state
    :title="__('shopper::pages/settings/webhooks.empty')"
    :content="__('shopper::pages/settings/webhooks.no_webhook')"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
        <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
        <ellipse class="fill-primary-600 opacity-10" cx="172" cy="244" rx="94" ry="14" />

        <rect class="fill-primary-500" x="96" y="128" width="104" height="88" rx="10" />
        <rect class="fill-primary-600" x="96" y="128" width="104" height="26" rx="10" />
        <g class="fill-sh-surface opacity-70">
            <circle cx="112" cy="141" r="4" />
            <circle cx="126" cy="141" r="4" />
        </g>
        <g class="stroke-sh-surface opacity-60" stroke-width="4" stroke-linecap="round">
            <path d="M112 174h56M112 192h40" />
        </g>

        <path class="stroke-primary-600" d="M212 172c34 0 34-42 62-46" stroke-width="6" stroke-linecap="round" stroke-dasharray="2 12" />
        <circle class="fill-amber-400" cx="282" cy="122" r="10" />
        <path class="stroke-primary-300" d="M296 100a34 34 0 0 1 10 22M268 100a34 34 0 0 0-10 22" stroke-width="6" stroke-linecap="round" />

        <path class="fill-primary-300" d="M78 92l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
        <path class="fill-amber-400" d="M236 226l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />
    </svg>
</x-shopper::empty-state>
