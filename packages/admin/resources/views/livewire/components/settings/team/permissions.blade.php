<div class="overflow-hidden">
    <div class="flex flex-wrap items-baseline px-4 2xl:px-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
            {{ __('shopper::pages/settings/staff.permissions') }}
        </h3>
        <p class="ml-2 mt-1 truncate text-sm leading-5 text-gray-500">
            {{ __('shopper::pages/settings/staff.permissions_in_role', ['name' => $role->display_name]) }}
        </p>
    </div>
    <div
        class="mt-4 divide-y divide-gray-200 bg-white overflow-x-auto border-t border-gray-200 dark:bg-transparent dark:divide-white/10 dark:border-white/10"
    >
        @foreach ($groupPermissions as $group => $permissions)
            <div>
                <div class="w-full bg-gray-50 px-4 py-1.5 dark:bg-white/5 lg:px-6">
                    <span
                        class="font-heading text-xs font-semibold uppercase leading-5 tracking-wider text-gray-500 dark:text-gray-400"
                    >
                        {{ ! empty($group) ? $group : __('shopper::pages/settings/staff.custom_permission') }}
                    </span>
                </div>
                <div class="divide-y divide-gray-200 py-1 dark:divide-white/10">
                    @foreach ($permissions as $permission)
                        <div class="flex items-center justify-between px-4 py-2.5 lg:px-6">
                            <div class="flex items-center space-x-3">
                                <input
                                    id="permission_{{ $permission->id }}"
                                    name="permissions"
                                    type="checkbox"
                                    class="dark:focus:offset-gray-800 size-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700"
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
                                @if ($permission->can_be_removed)
                                    <button
                                        wire:click="removePermission({{ $permission->id }})"
                                        type="button"
                                        class="text-medium inline-flex items-center text-sm leading-5 text-gray-500 hover:text-red-500 focus:text-red-700 focus:shadow-none focus:outline-none dark:text-gray-400 dark:hover:text-red-500"
                                    >
                                        <x-untitledui-trash-03 class="size-5" aria-hidden="true" />
                                    </button>
                                @endif

                                @if ($permission->users->count() > 0)
                                    <div class="flex items-center space-x-2">
                                        <div class="flex shrink-0 -space-x-1">
                                            @foreach ($permission->users->limit(3) as $user)
                                                <img
                                                    class="shadow-solid size-6 max-w-none rounded-full"
                                                    src="{{ $user->picture }}"
                                                    alt=""
                                                />
                                            @endforeach
                                        </div>
                                        @if ($permission->users->count() - 3 > 0)
                                            <span
                                                class="shrink-0 text-xs font-medium leading-5 text-gray-500 dark:text-gray-400"
                                            >
                                                +{{ $permission->users->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <time
                                    datetime="{{ $permission->created_at->format('Y-m-d') }}"
                                    class="text-xs font-medium capitalize leading-5 text-gray-400 dark:text-gray-500"
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
