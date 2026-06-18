<x-shopper::empty-state
    :title="__('shopper::pages/products.title')"
    :content="__('shopper::pages/products.content')"
    :button="__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/products.single')])"
    panel="{ component: 'shopper-slide-overs.add-product' }"
    permission="products.create"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
            <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
            <ellipse class="fill-primary-600 opacity-10" cx="180" cy="252" rx="92" ry="17" />

            <path class="fill-primary-300" d="M64 86l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
            <path class="fill-amber-400" d="M300 112l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />
            <circle class="fill-primary-200" cx="78" cy="196" r="6" />

            <g transform="rotate(-9 250 110)">
                <rect class="fill-sh-surface stroke-sh-border" x="214" y="74" width="78" height="92" rx="10" stroke-width="1.5" />
                <rect class="fill-primary-100" x="226" y="86" width="54" height="40" rx="6" />
                <path class="fill-primary-400" d="M226 116l14-13 10 9 12-14 18 19v9a6 6 0 0 1-6 6h-42a6 6 0 0 1-6-6z" />
                <circle class="fill-primary-300" cx="240" cy="100" r="5" />
                <rect class="fill-sh-border" x="226" y="136" width="44" height="6" rx="3" />
                <rect class="fill-sh-muted" x="226" y="148" width="30" height="6" rx="3" />
            </g>

            <g transform="rotate(-20 96 132)">
                <rect class="fill-primary-200" x="64" y="112" width="58" height="38" rx="9" />
                <circle class="fill-sh-surface" cx="76" cy="124" r="5" />
                <path class="stroke-primary-600" d="M108 110l14-18" stroke-width="4" stroke-linecap="round" />
                <rect class="fill-primary-500" x="84" y="124" width="28" height="5" rx="2.5" />
                <rect class="fill-primary-400" x="84" y="134" width="18" height="5" rx="2.5" />
            </g>

            <path class="fill-primary-600" d="M112 150l68-27 68 27-68 27z" />
            <path class="fill-primary-500" d="M112 150l68 27v78l-68-27z" />
            <path class="fill-primary-400" d="M248 150l-68 27v78l68-27z" />
            <path class="fill-primary-300" d="M112 150l68-27 34 13.5-68 27z" />
            <path class="fill-primary-200" d="M248 150l-68-27-34 13.5 68 27z" />
            <path class="fill-sh-surface opacity-70" d="M146 136.5l68 27-4 8-68-27z" />
        </svg>
</x-shopper::empty-state>
