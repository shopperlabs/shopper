@props(['unreadNotificationsCount' => 0])

<button
    type="button"
    @class([
        'text-sh-header-fg-muted hover:text-sh-header-fg hover:bg-sh-header-hover relative inline-flex size-9 items-center justify-center rounded-lg',
    ])
    aria-label="{{ __('shopper::notifications.database.open') }}"
>
    <x-untitledui-bell-02 class="size-5" stroke-width="1.5" aria-hidden="true" />

    @if ($unreadNotificationsCount)
        <span
            class="bg-primary-600 absolute -top-0.5 -right-0.5 inline-flex min-w-4 items-center justify-center rounded-full px-1 text-[10px] font-semibold leading-4 text-white"
        >
            {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
        </span>
    @endif
</button>
