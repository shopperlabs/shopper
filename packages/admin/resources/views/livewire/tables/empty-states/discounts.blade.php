<x-shopper::empty-state
    :title="__('shopper::pages/discounts.title')"
    :content="__('shopper::pages/discounts.description')"
    :button="__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/discounts.single')])"
    permission="discounts.create"
    panel="{ component: 'shopper-slide-overs.add-promotion' }"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
        <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
        <ellipse class="fill-primary-600 opacity-10" cx="172" cy="240" rx="96" ry="16" />

        <path class="fill-primary-300" d="M82 116l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
        <path class="fill-amber-400" d="M300 110l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />

        <g transform="rotate(-15 268 102)">
            <rect class="fill-primary-200" x="232" y="78" width="44" height="30" rx="8" />
            <circle class="fill-sh-surface" cx="243" cy="89" r="4.5" />
        </g>
        <g transform="rotate(-15 280 116)">
            <rect class="fill-primary-500" x="252" y="96" width="50" height="34" rx="9" />
            <circle class="fill-sh-surface" cx="264" cy="108" r="5" />
            <text x="282" y="120" font-size="18" font-weight="700" class="fill-sh-surface" text-anchor="middle">%</text>
        </g>

        <path class="fill-primary-300" d="M128 112h22l4 24h-30z" />
        <path class="fill-primary-400" d="M156 104h22l5 32h-32z" />

        <path class="fill-primary-500" d="M104 132h116l-15 62h-86z" />
        <path class="fill-primary-600" d="M104 132h116l-4 18H108z" />
        <g class="fill-sh-surface opacity-50">
            <rect x="130" y="136" width="3.4" height="52" />
            <rect x="152" y="136" width="3.4" height="52" />
            <rect x="174" y="136" width="3.4" height="52" />
            <rect x="196" y="136" width="3.4" height="52" />
        </g>
        <path class="stroke-primary-600" d="M76 108h20l9 26 13 60" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" />
        <circle class="fill-sh-fg" cx="128" cy="210" r="11" />
        <circle class="fill-sh-surface" cx="128" cy="210" r="4" />
        <circle class="fill-sh-fg" cx="190" cy="210" r="11" />
        <circle class="fill-sh-surface" cx="190" cy="210" r="4" />
    </svg>
</x-shopper::empty-state>
