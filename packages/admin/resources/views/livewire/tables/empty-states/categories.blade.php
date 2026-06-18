<x-shopper::empty-state
    :title="__('shopper::pages/categories.title')"
    :content="__('shopper::pages/categories.content')"
    :button="__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/categories.single')])"
    permission="categories.create"
    panel="{ component: 'shopper-slide-overs.category-form' }"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
        <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
        <ellipse class="fill-primary-600 opacity-10" cx="180" cy="248" rx="92" ry="16" />

        <path class="fill-primary-300" d="M82 110l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
        <path class="fill-amber-400" d="M288 138l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />

        <path class="stroke-primary-300" d="M180 142v22M118 164h124M118 164v18M242 164v18" stroke-width="3.5" stroke-linecap="round" />

        <path class="fill-primary-600" d="M152 92h28l7 11h-35z" />
        <rect class="fill-primary-500" x="150" y="100" width="60" height="44" rx="9" />
        <rect class="fill-sh-surface opacity-30" x="160" y="116" width="40" height="7" rx="3.5" />

        <path class="fill-primary-600" d="M92 178h22l6 9h-28z" />
        <rect class="fill-primary-400" x="90" y="184" width="56" height="40" rx="8" />
        <path class="fill-primary-600" d="M218 178h22l6 9h-28z" />
        <rect class="fill-primary-400" x="216" y="184" width="56" height="40" rx="8" />
    </svg>
</x-shopper::empty-state>
