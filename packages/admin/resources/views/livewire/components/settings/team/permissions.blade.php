<div class="overflow-hidden">
    <div class="flex flex-wrap items-baseline px-4 2xl:px-6">
        <h3 class="text-lg leading-6 font-medium text-sh-fg">
            {{ __('shopper::pages/settings/staff.permissions') }}
        </h3>
        <p class="mt-1 ml-2 truncate text-sm leading-5 text-sh-fg-muted">
            {{ __('shopper::pages/settings/staff.permissions_in_role', ['name' => $role->display_name]) }}
        </p>
    </div>
    <div
        class="mt-4 divide-y divide-sh-border overflow-x-auto border-t border-sh-border bg-sh-surface dark:bg-transparent"
    >
        @foreach ($groupPermissions as $group => $permissions)
            <div>
                <div class="w-full bg-sh-muted px-4 py-1.5 lg:px-6">
                    <span
                        class="font-heading text-xs leading-5 font-semibold tracking-wider text-sh-fg-muted uppercase"
                    >
                        {{ ! empty($group) ? $group : __('shopper::pages/settings/staff.custom_permission') }}
                    </span>
                </div>
                <div class="divide-y divide-sh-border py-1">
                    @foreach ($permissions as $permission)
                        <div class="flex items-center justify-between px-4 py-2.5 lg:px-6">
                            <div class="flex items-center space-x-3">
                                <input
                                    id="permission_{{ $permission->id }}"
                                    name="permissions"
                                    type="checkbox"
                                    class="dark:focus:offset-gray-800 text-primary-600 focus:ring-primary-500 size-4 rounded border-sh-border"
                                    wire:key="{{ $permission->id }}"
                                    value="{{ $permission->id }}"
                                    aria-label="permission_{{ $permission->id }}"
                                    @click="$wire.togglePermission({{ $permission->id }})"
                                    @checked($role->hasPermissionTo($permission->name))
                                />
                                <x-shopper::label
                                    for="permission_{{ $permission->id }}"
                                    :value="__($permission->display_name)"
                                />
                            </div>
                            <div class="flex items-center space-x-3">
                                @if ($permission->can_be_removed && auth(config('shopper.auth.guard'))->user()?->isAdmin())
                                    <button
                                        wire:click="removePermission({{ $permission->id }})"
                                        type="button"
                                        class="text-medium inline-flex items-center text-sm leading-5 text-sh-fg-muted hover:text-red-500 focus:text-red-700 focus:shadow-none focus:outline-none dark:hover:text-red-500"
                                    >
                                        <x-untitledui-trash-03 class="size-5" aria-hidden="true" />
                                    </button>
                                @endif

                                @if ($permission->users->count() > 0)
                                    <div class="flex items-center space-x-2">
                                        <div class="flex shrink-0 -space-x-1">
                                            @foreach ($permission->users->take(3) as $user)
                                                <img
                                                    class="shadow-solid size-6 max-w-none rounded-full"
                                                    src="{{ $user->picture }}"
                                                    alt=""
                                                />
                                            @endforeach
                                        </div>
                                        @if ($permission->users->count() - 3 > 0)
                                            <span
                                                class="shrink-0 text-xs leading-5 font-medium text-sh-fg-muted"
                                            >
                                                +{{ $permission->users->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <time
                                    datetime="{{ $permission->created_at->format('Y-m-d') }}"
                                    class="text-xs leading-5 font-medium text-sh-fg-muted capitalize"
                                >
                                    {{ $permission->created_at->translatedFormat('j F') }}
                                </time>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
