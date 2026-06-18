<x-shopper::empty-state
    :title="__('shopper::pages/tags.title')"
    :content="__('shopper::pages/tags.content')"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
        <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
        <ellipse class="fill-primary-600 opacity-10" cx="180" cy="242" rx="86" ry="16" />

        <path class="fill-primary-300" d="M86 104l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
        <path class="fill-amber-400" d="M288 142l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />

        <g transform="rotate(14 168 168)">
            <path class="fill-primary-300" d="M146 152l24-24h58a10 10 0 0 1 10 10v34a10 10 0 0 1-10 10h-58z" />
            <circle class="fill-sh-surface" cx="165" cy="152" r="6" />
        </g>

        <g transform="rotate(-12 158 150)">
            <path class="fill-primary-500" d="M120 150l28-28h66a10 10 0 0 1 10 10v36a10 10 0 0 1-10 10h-66z" />
            <circle class="fill-sh-surface" cx="140" cy="150" r="7" />
            <rect class="fill-primary-300" x="166" y="138" width="40" height="7" rx="3.5" />
            <rect class="fill-primary-400" x="166" y="152" width="26" height="7" rx="3.5" />
        </g>
    </svg>
</x-shopper::empty-state>
