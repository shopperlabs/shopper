<div class="mt-10 sm:mt-0">
    <div class="lg:grid lg:grid-cols-3 lg:gap-x-12 lg:gap-y-6">
        <x-shopper::section-heading
            class="lg:col-span-1"
            :title="__('shopper::pages/auth.account.passkeys_title')"
            :description="__('shopper::pages/auth.account.passkeys_description')"
        />
        <div class="mt-5 lg:col-span-2 lg:mt-0 lg:max-w-3xl">
            <x-shopper::card
                x-data="passkeyManager({
                    optionsUrl: '{{ route('shopper.passkeys.registration-options') }}',
                    storeUrl: '{{ route('shopper.passkeys.store') }}',
                })"
                x-on:shopper-passkey-register.window="register($event.detail.name)"
            >
                <x-slot name="title">
                    <div class="flex items-center gap-x-3">
                        <div
                            @class([
                                'size-2.5 shrink-0 rounded-full',
                                'bg-success-400' => $this->passkeys->isNotEmpty(),
                                'bg-sh-fg-muted' => $this->passkeys->isEmpty(),
                            ])
                        ></div>
                        <h3 class="text-base leading-6 font-medium text-sh-fg">
                            {{ trans_choice('shopper::pages/auth.account.passkeys_count', $this->passkeys->count(), ['count' => $this->passkeys->count()]) }}
                        </h3>
                    </div>
                </x-slot>

                <div class="space-y-6 py-2">
                    <div class="flex items-start">
                        <div class="shrink-0">
                            <span class="block size-12">
                                <x-heroicon-o-finger-print class="text-primary-600 h-full w-full" aria-hidden="true" />
                            </span>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm leading-5 text-sh-fg-muted">
                                {{ __('shopper::pages/auth.account.passkeys_secure') }}
                            </p>
                        </div>
                    </div>

                    @if ($this->passkeys->isNotEmpty())
                        <ul class="divide-y divide-sh-border border-t border-sh-border" role="list">
                            @foreach ($this->passkeys as $passkey)
                                <li class="flex items-center justify-between gap-x-4 py-4" wire:key="passkey-{{ $passkey->getKey() }}">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-medium text-sh-fg">
                                            {{ $passkey->name }}
                                        </p>
                                        <p class="mt-1 text-xs leading-5 text-sh-fg-muted">
                                            {{ __('shopper::pages/auth.account.passkey_added', ['date' => $passkey->created_at->diffForHumans()]) }}
                                            &middot;
                                            {{
                                                $passkey->last_used_at
                                                    ? __('shopper::pages/auth.account.passkey_last_used', ['date' => $passkey->last_used_at->diffForHumans()])
                                                    : __('shopper::pages/auth.account.passkey_never_used')
                                            }}
                                        </p>
                                    </div>
                                    {{ ($this->deletePasskeyAction)(['passkey' => $passkey->getKey()]) }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="flex items-center justify-between gap-x-4 py-2">
                    <p x-cloak x-show="! supported" class="text-sm leading-5 text-sh-fg-muted">
                        {{ __('shopper::pages/auth.account.passkeys_unsupported') }}
                    </p>
                    <div class="ml-auto">
                        <x-filament::button
                            wire:click="startConfirmingPassword('openAddPasskeyModal')"
                            wire:loading.attr="disabled"
                            type="button"
                            x-bind:disabled="! supported"
                        >
                            <x-shopper::loader wire:loading wire:target="startConfirmingPassword" class="text-white" />
                            {{ __('shopper::pages/auth.account.passkey_add') }}
                        </x-filament::button>
                    </div>
                </div>
            </x-shopper::card>
        </div>
    </div>

    <x-filament-actions::modals />
</div>
