<x-shopper::empty-state
    :title="__('shopper::pages/campaigns.title')"
    :content="__('shopper::pages/campaigns.description')"
    :button="__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/campaigns.single')])"
    permission="campaigns.create"
    :url="route('shopper.campaigns.create')"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
        <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
        <ellipse class="fill-primary-600 opacity-10" cx="170" cy="240" rx="86" ry="16" />

        <path class="fill-primary-300" d="M88 104l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
        <path class="fill-amber-400" d="M298 150l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />

        <path class="stroke-primary-400" d="M236 116a44 44 0 0 1 0 78" stroke-width="4.5" stroke-linecap="round" />
        <path class="stroke-primary-300" d="M252 100a66 66 0 0 1 0 110" stroke-width="4.5" stroke-linecap="round" />

        <g transform="rotate(8 96 116)">
            <rect class="fill-primary-200" x="74" y="100" width="44" height="30" rx="8" />
            <circle class="fill-sh-surface" cx="85" cy="111" r="4.5" />
            <text x="100" y="120" font-size="15" font-weight="700" class="fill-primary-700" text-anchor="middle">%</text>
        </g>

        <rect class="fill-primary-700" x="150" y="176" width="14" height="34" rx="6" transform="rotate(10 157 193)" />
        <path class="fill-primary-500" d="M112 156l92-38v82l-92-26z" />
        <path class="fill-primary-600" d="M112 156l18-7.5v54l-18-7.5z" />
        <ellipse class="fill-primary-600" cx="204" cy="159" rx="7" ry="41" />
        <ellipse class="fill-primary-400" cx="204" cy="159" rx="3.5" ry="32" />
    </svg>
</x-shopper::empty-state>
