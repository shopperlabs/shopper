<div class="flex items-center space-x-2">
    @if ($getRecord()->email_verified_at)
        <x-untitledui-check-verified-02 class="size-5 text-green-500" aria-hidden="true" />
    @else
        <x-untitledui-alert-circle class="size-5 text-danger-500" aria-hidden="true" />
    @endif
    <span class="text-sm leading-5">
        {{ $getRecord()->email }}
    </span>
</div>
