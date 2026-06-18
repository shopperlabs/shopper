<x-shopper::empty-state
    :title="__('shopper::pages/orders.abandoned_carts.empty')"
    :content="__('shopper::pages/orders.abandoned_carts.empty_description')"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
        <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
        <ellipse class="fill-primary-600 opacity-10" cx="166" cy="240" rx="92" ry="16" />

        <path class="fill-primary-300" d="M84 118l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
        <path class="fill-amber-400" d="M298 158l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />

        <path class="fill-primary-300" d="M150 110h22l4 24h-30z" />

        <path class="fill-primary-500" d="M100 132h116l-15 62h-86z" />
        <path class="fill-primary-600" d="M100 132h116l-4 18H104z" />
        <g class="fill-sh-surface opacity-50">
            <rect x="126" y="136" width="3.4" height="52" />
            <rect x="148" y="136" width="3.4" height="52" />
            <rect x="170" y="136" width="3.4" height="52" />
            <rect x="192" y="136" width="3.4" height="52" />
        </g>
        <path class="stroke-primary-600" d="M72 108h20l9 26 13 60" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" />
        <circle class="fill-sh-fg" cx="124" cy="210" r="11" />
        <circle class="fill-sh-surface" cx="124" cy="210" r="4" />
        <circle class="fill-sh-fg" cx="186" cy="210" r="11" />
        <circle class="fill-sh-surface" cx="186" cy="210" r="4" />

        <circle class="fill-primary-500" cx="252" cy="112" r="22" />
        <circle class="fill-sh-surface" cx="252" cy="112" r="15" />
        <path class="stroke-primary-600" d="M252 112v-9M252 112l7 5" stroke-width="3" stroke-linecap="round" />
    </svg>
</x-shopper::empty-state>
