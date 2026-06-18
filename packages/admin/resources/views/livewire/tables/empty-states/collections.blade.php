<x-shopper::empty-state
    :title="__('shopper::pages/collections.title')"
    :content="__('shopper::pages/collections.content')"
    :button="__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/collections.single')])"
    permission="collections.create"
    panel="{ component: 'shopper-slide-overs.add-collection-form' }"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
        <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
        <ellipse class="fill-primary-600 opacity-10" cx="180" cy="252" rx="92" ry="17" />

        <path class="fill-primary-300" d="M84 104l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
        <path class="fill-amber-400" d="M288 150l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />

        <g transform="rotate(12 254 100)">
            <path class="fill-primary-300" d="M236 92h36l4 32h-44z" />
            <path class="stroke-primary-600" d="M246 92a8 8 0 0 1 16 0" stroke-width="3" />
        </g>

        <path class="fill-primary-600" d="M118 108h54l10 16h-64z" />
        <rect class="fill-primary-500" x="118" y="120" width="150" height="112" rx="14" />
        <rect class="fill-sh-surface stroke-sh-border" x="150" y="118" width="94" height="64" rx="9" stroke-width="1.5" />
        <rect class="fill-primary-100" x="160" y="128" width="36" height="44" rx="6" />
        <path class="fill-primary-400" d="M170 138l5-4 4 4 4-4 5 4-3 5v17h-12v-17z" />
        <rect class="fill-sh-border" x="204" y="132" width="30" height="7" rx="3.5" />
        <rect class="fill-sh-muted" x="204" y="146" width="22" height="7" rx="3.5" />
        <path class="fill-primary-400" d="M118 156h150v62a14 14 0 0 1-14 14H132a14 14 0 0 1-14-14z" />
        <rect class="fill-primary-300" x="160" y="188" width="40" height="8" rx="4" />
    </svg>
</x-shopper::empty-state>
