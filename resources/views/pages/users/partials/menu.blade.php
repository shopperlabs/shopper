<div class="mt-6 md:flex md:items-center md:justify-between">
    <div class="flex-1 min-w-0">
        <h2 class="text-2xl font-bold leading-7 text-gray-600 sm:text-3xl sm:leading-9 sm:truncate pb-4 border-b border-gray-200">{{ __('My profile') }}</h2>
    </div>
</div>

<div class="mt-6">
    <div class="sm:hidden">
        <select class="form-select block w-full">
            <option selected>{{ __("My Account") }}</option>
            <option>{{ __("Security") }}</option>
        </select>
    </div>
    <div class="hidden sm:block">
        <nav class="flex space-x-4">
            <a href="{{ route('shopper.profile', 'profile') }}" class="px-3 py-2 font-medium text-sm leading-5 rounded-md @if(request()->is(shopper_prefix(). '/profile/profile')) text-gray-800 bg-gray-200 focus:outline-none focus:bg-gray-300 @else text-gray-600 focus:text-gray-800 hover:text-gray-800 focus:bg-gray-200 @endif focus:outline-none">
                {{ __("My Account") }}
            </a>
            <a href="{{ route('shopper.profile', 'security') }}" class="px-3 py-2 font-medium text-sm leading-5 rounded-md @if(request()->is(shopper_prefix(). '/profile/security')) text-gray-800 bg-gray-200 focus:outline-none focus:bg-gray-300 @else text-gray-600 focus:text-gray-800 hover:text-gray-800 focus:bg-gray-200 @endif focus:outline-none">
                {{ __("Security") }}
            </a>
        </nav>
    </div>
</div>
