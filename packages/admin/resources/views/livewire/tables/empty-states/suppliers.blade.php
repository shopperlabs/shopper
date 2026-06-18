<x-shopper::empty-state
    :title="__('shopper::pages/suppliers.title')"
    :content="__('shopper::pages/suppliers.content')"
    :button="__('shopper::forms.actions.add_label', ['label' => __('shopper::pages/suppliers.single')])"
    panel="{ component: 'shopper-slide-overs.supplier-form' }"
    permission="suppliers.create"
>
    <svg class="h-44 w-auto sm:h-52" viewBox="0 0 360 300" fill="none" aria-hidden="true">
        <ellipse class="fill-primary-500 opacity-[0.08] dark:opacity-[0.16]" cx="180" cy="160" rx="150" ry="120" />
        <ellipse class="fill-primary-600 opacity-10" cx="180" cy="232" rx="110" ry="17" />

        <path class="fill-primary-300" d="M88 96l5 11 11 5-11 5-5 11-5-11-11-5 11-5z" />
        <path class="fill-amber-400" d="M292 120l3.4 7.4 7.4 3.4-7.4 3.4-3.4 7.4-3.4-7.4-7.4-3.4 7.4-3.4z" />

        <path class="fill-primary-300" d="M86 150l32-13 32 13-32 13z" />
        <path class="fill-primary-500" d="M86 150l32 13v36l-32-13z" />
        <path class="fill-primary-600" d="M150 150l-32 13v36l32-13z" />
        <path class="fill-sh-surface opacity-60" d="M104 142.5l32 13-3 6-32-13z" />

        <rect class="fill-primary-500" x="172" y="150" width="68" height="52" rx="6" />
        <path class="fill-primary-600" d="M240 164h20l16 18v20h-36z" />
        <rect class="fill-primary-200" x="247" y="169" width="17" height="13" rx="3" />
        <circle class="fill-sh-fg" cx="196" cy="206" r="11" />
        <circle class="fill-sh-surface" cx="196" cy="206" r="4" />
        <circle class="fill-sh-fg" cx="256" cy="206" r="11" />
        <circle class="fill-sh-surface" cx="256" cy="206" r="4" />
    </svg>
</x-shopper::empty-state>
