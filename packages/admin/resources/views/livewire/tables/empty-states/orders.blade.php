<x-shopper::empty-state
    :title="__('shopper::pages/orders.title')"
    :content="__('shopper::pages/orders.content')"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
        <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
        <ellipse class="fill-primary-600 opacity-10" cx="180" cy="248" rx="86" ry="16" />

        <path class="fill-primary-300" d="M86 110l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
        <path class="fill-amber-400" d="M290 150l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />

        <g transform="rotate(-9 142 168)">
            <path class="fill-sh-surface stroke-sh-border" d="M112 116h46v96l-7-5-8 5-7-5-8 5-7-5-9 5z" stroke-width="1.5" />
            <rect class="fill-sh-muted" x="122" y="130" width="26" height="6" rx="3" />
            <rect class="fill-sh-muted" x="122" y="144" width="18" height="6" rx="3" />
            <circle class="fill-primary-500" cx="135" cy="176" r="11" />
            <path class="stroke-sh-surface" d="M130 176l4 4 7-8" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
        </g>

        <path class="stroke-primary-600" d="M198 142v-8a20 20 0 0 1 40 0v8" stroke-width="4.5" stroke-linecap="round" />
        <path class="fill-primary-500" d="M180 140h78l8 84a10 10 0 0 1-10 11h-74a10 10 0 0 1-10-11z" />
        <path class="fill-primary-400" d="M180 140h78l-2 18h-74z" />
        <rect class="fill-sh-surface opacity-50" x="206" y="178" width="26" height="8" rx="4" />
    </svg>
</x-shopper::empty-state>
