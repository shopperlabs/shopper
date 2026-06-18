<x-shopper::empty-state
    :title="__('shopper::pages/attributes.title')"
    :content="__('shopper::pages/attributes.content')"
    :button="__('shopper::pages/attributes.add')"
    permission="attributes.create"
    panel="{ component: 'shopper-slide-overs.attribute-form' }"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
        <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
        <ellipse class="fill-primary-600 opacity-10" cx="180" cy="244" rx="86" ry="16" />

        <path class="fill-primary-300" d="M86 104l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
        <path class="fill-amber-400" d="M286 134l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />

        <g transform="rotate(-16 262 110)">
            <path class="fill-primary-400" d="M244 94h26a6 6 0 0 1 6 6v26l-19 12-19-12v-26a6 6 0 0 1 6-6z" />
            <circle class="fill-sh-surface" cx="257" cy="106" r="5" />
        </g>

        <rect class="fill-sh-surface stroke-sh-border" x="104" y="104" width="152" height="110" rx="16" stroke-width="1.5" />
        <rect class="fill-primary-100" x="120" y="120" width="64" height="10" rx="5" />
        <circle class="fill-primary-200" cx="130" cy="156" r="11" />
        <circle class="fill-primary-300" cx="158" cy="156" r="11" />
        <circle class="fill-primary-400" cx="186" cy="156" r="11" />
        <circle class="fill-primary-500" cx="214" cy="156" r="11" />
        <rect class="fill-primary-100" x="120" y="184" width="34" height="16" rx="8" />
        <rect class="fill-primary-200" x="162" y="184" width="34" height="16" rx="8" />
        <rect class="fill-primary-100" x="204" y="184" width="34" height="16" rx="8" />
    </svg>
</x-shopper::empty-state>
