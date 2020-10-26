<div class="bg-white shadow overflow-hidden sm:rounded-md">
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
                    <span class="text-gray-900 capitalize tracking-wide text-sm font-medium leading-5">{{ $group }}</span>
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
