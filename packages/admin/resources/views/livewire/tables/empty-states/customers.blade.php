<x-shopper::empty-state
    :title="__('shopper::pages/customers.title')"
    :content="__('shopper::pages/customers.content')"
    :button="__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/customers.single')])"
    permission="customers.create"
    :url="route('shopper.customers.create')"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
        <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
        <ellipse class="fill-primary-600 opacity-10" cx="180" cy="246" rx="86" ry="16" />

        <path class="fill-primary-300" d="M86 108l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
        <path class="fill-amber-400" d="M288 142l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />

        <path class="fill-primary-500" d="M256 96c-5-8-19-6-19 5 0 9 19 19 19 19s19-10 19-19c0-11-14-13-19-5z" />

        <rect class="fill-sh-surface stroke-sh-border" x="128" y="120" width="124" height="98" rx="14" transform="rotate(-7 190 169)" stroke-width="1.5" />
        <rect class="fill-sh-surface stroke-sh-border" x="118" y="122" width="128" height="100" rx="14" stroke-width="1.5" />
        <rect class="fill-primary-100" x="134" y="138" width="50" height="50" rx="11" />
        <circle class="fill-primary-400" cx="159" cy="157" r="11" />
        <path class="fill-primary-300" d="M142 187a17 17 0 0 1 34 0z" />
        <rect class="fill-sh-border" x="196" y="142" width="44" height="7" rx="3.5" />
        <rect class="fill-sh-muted" x="196" y="158" width="32" height="7" rx="3.5" />
        <rect class="fill-sh-muted" x="196" y="174" width="38" height="7" rx="3.5" />
    </svg>
</x-shopper::empty-state>
