<div class="overflow-hidden">
    <div class="px-4 2xl:px-6 flex flex-wrap items-baseline">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ __('shopper::pages/settings.roles_permissions.permissions') }}
        </h3>
        <p class="ml-2 mt-1 text-sm leading-5 text-gray-500 truncate">
            {{ __('shopper::pages/settings.roles_permissions.permissions_in_role', ['name' => $role->display_name]) }}
        </p>
    </div>
    <div class="mt-4 border-t border-gray-200 overflow-x-auto divide-y divide-gray-200 dark:border-gray-700 dark:divide-gray-700">
        @foreach($groupPermissions as $group => $permissions)
            <div>
                <div class="w-full py-1.5 px-4 lg:px-6 bg-gray-50 dark:bg-white/5">
                    <span class="text-xs uppercase font-semibold font-heading leading-5 tracking-wider text-gray-500 dark:text-gray-400">
                        {{ ! empty($group) ? $group : __('shopper::pages/settings.roles_permissions.custom_permission') }}
                    </span>
                </div>
                <div class="py-1 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($permissions as $permission)
                        <div class="flex items-center justify-between px-4 lg:px-6 py-2.5">
                            <div class="flex items-center space-x-3">
                                <input
                                   id="permission_{{ $permission->id }}"
                                   name="permissions"
                                   type="checkbox"
                                   class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:focus:offset-gray-800"
                                   wire:key="{{ $permission->id }}"
                                   value="{{ $permission->id }}"
                                   aria-label="permission_{{ $permission->id }}"
                                   @click="$wire.togglePermission({{ $permission->id }})"
                                    @checked($role->hasPermissionTo($permission->name))
                                />
                                <x-shopper::label for="permission_{{ $permission->id }}" :value="__($permission->display_name)" />
                            </div>
                            <div class="flex items-center space-x-3">
                                @if($permission->can_be_removed)
                                    <button wire:click="removePermission({{ $permission->id }})" type="button" class="inline-flex items-center text-sm leading-5 text-medium text-gray-500 hover:text-red-500 focus:text-red-700 focus:outline-none focus:shadow-none dark:text-gray-400 dark:hover:text-red-500">
                                        <x-untitledui-trash-03 class="w-5 h-5" aria-hidden="true" />
                                    </button>
                                @endif
                                @if($permission->users->count() > 0)
                                    <div class="flex items-center space-x-2">
                                        <div class="flex shrink-0 -space-x-1">
                                            @foreach($permission->users->limit(3) as $user)
                                                <img class="max-w-none h-6 w-6 rounded-full shadow-solid" src="{{ $user->picture }}" alt="">
                                            @endforeach
                                        </div>
                                        @if($permission->users->count() - 3 > 0)
                                            <span class="shrink-0 text-xs leading-5 font-medium text-gray-500 dark:text-gray-400">
                                                +{{ $permission->users->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                <time datetime="{{ $permission->created_at->format('Y-m-d') }}" class="capitalize text-xs font-medium leading-5 text-gray-400 dark:text-gray-500">
                                    {{ $permission->created_at->formatLocalized('%b %d') }}
                                </time>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>