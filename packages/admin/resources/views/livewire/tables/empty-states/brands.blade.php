<x-shopper::empty-state
    :title="__('shopper::pages/brands.title')"
    :content="__('shopper::pages/brands.content')"
    :button="__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/brands.single')])"
    panel="{ component: 'shopper-slide-overs.brand-form' }"
    permission="brands.create"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
        <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
        <ellipse class="fill-primary-600 opacity-10" cx="180" cy="252" rx="80" ry="16" />

        <path class="fill-primary-300" d="M84 110l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
        <path class="fill-amber-400" d="M286 132l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />

        <g transform="rotate(-18 96 116)">
            <rect class="fill-primary-200" x="74" y="100" width="44" height="28" rx="8" />
            <circle class="fill-sh-surface" cx="85" cy="111" r="4" />
            <rect class="fill-primary-400" x="94" y="108" width="18" height="5" rx="2.5" />
        </g>

        <path class="fill-primary-600" d="M150 168h60l-12 50-18-14-18 14z" />
        <rect class="fill-primary-500" x="136" y="86" width="88" height="88" rx="22" />
        <rect class="fill-primary-400" x="150" y="100" width="60" height="60" rx="15" />
        <path class="fill-sh-surface opacity-90" d="M180 112l6.8 13.8 15.2 2.2-11 10.7 2.6 15.1L180 146.6l-13.6 7.2 2.6-15.1-11-10.7 15.2-2.2z" />
    </svg>
</x-shopper::empty-state>
