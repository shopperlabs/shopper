<x-shopper::empty-state
    :title="__('shopper::pages/orders.shipment.empty')"
    :content="__('shopper::pages/orders.shipment.empty_description')"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
        <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
        <ellipse class="fill-primary-600 opacity-10" cx="158" cy="244" rx="80" ry="16" />

        <path class="fill-primary-300" d="M84 102l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
        <path class="fill-amber-400" d="M300 120l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />

        <path class="stroke-primary-300" d="M196 150c34-14 46 22 70 12" stroke-width="3.5" stroke-linecap="round" stroke-dasharray="1 10" />

        <path class="fill-primary-300" d="M110 152l36-15 36 15-36 15z" />
        <path class="fill-primary-500" d="M110 152l36 15v42l-36-15z" />
        <path class="fill-primary-600" d="M182 152l-36 15v42l36-15z" />
        <path class="fill-sh-surface opacity-60" d="M128 144.5l36 15-3 6-36-15z" />

        <g transform="translate(274 132)">
            <path class="fill-primary-500" d="M0 0a18 18 0 0 1 18 18c0 13-18 28-18 28S-18 31-18 18A18 18 0 0 1 0 0z" />
            <circle class="fill-sh-surface" cx="0" cy="18" r="7" />
        </g>
    </svg>
</x-shopper::empty-state>
