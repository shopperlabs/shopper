<div class="bg-white shadow overflow-hidden rounded-md">
    <div class="px-4 pt-5 sm:px-6 flex flex-wrap items-baseline">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            {{ __("Permissions") }}
        </h3>
        <p class="ml-2 mt-1 text-sm leading-5 text-gray-500 truncate">{{ __("in") }} {{ $role->display_name }} {{ __("role") }}</p>
    </div>
    <div class="mt-4 border-t border-gray-200 overflow-x-auto divide-y divide-gray-200">
        @foreach($groupPermissions as $group => $permissions)
            <div>
                <div class="full bg-cool-gray-100 py-1.5 px-4">
                    <span class="text-gray-900 capitalize tracking-wide text-sm font-medium leading-5">{{ ! empty($group) ? $group : __("Custom permissions") }}</span>
                </div>
                <div class="px-4 py-1 divide-y divide-gray-200">
                    @foreach($permissions as $permission)
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center space-x-3">
                                <input
                                   id="permission_{{ $permission->id }}"
                                   type="checkbox"
                                   class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
                                   value="{{ $permission->id }}"
                                   @click="togglePermission({{ $permission->id }})"
                                   @if($role->hasPermissionTo($permission->name)) checked @endif
                                />
                                <label for="permission_{{ $permission->id }}" class="cursor-pointer text-sm leading-5 font-medium text-gray-700">{{ $permission->display_name }}</label>
                            </div>
                            <div class="flex items-center space-x-3">
                                @if($permission->can_be_removed)
                                    <button wire:click="removePermission({{ $permission->id }})" type="button" class="inline-flex items-center text-sm leading-5 text-medium text-gray-500 hover:text-red-500 focus:text-red-700 focus:outline-none focus:shadow-none transition duration-150 ease-in-out">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                                @if($permission->users->count() > 0)
                                    <div class="flex items-center space-x-2">
                                        <div class="flex flex-shrink-0 -space-x-1">
                                            @foreach($permission->users->limit(3) as $user)
                                                <img class="max-w-none h-6 w-6 rounded-full text-white shadow-solid" src="{{ $user->picture }}" alt="">
                                            @endforeach
                                        </div>
                                        @if($permission->users->count() - 3 > 0)
                                            <span class="flex-shrink-0 text-xs leading-5 font-medium text-gray-600">+{{ $permission->users->count() - 3 }}</span>
                                        @endif
                                    </div>
                                @endif
                                <time datetime="{{ $permission->created_at->format('Y-m-d') }}" class="capitalize text-xs font-medium leading-5 text-gray-400">{{ $permission->created_at->formatLocalized('%b %d') }}</time>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
    <script>
        function togglePermission(id) {
            window.livewire.emit('togglePermission', id)
        }
    </script>
@endpush
