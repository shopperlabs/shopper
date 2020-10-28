<div class="flex items-center">
    {{ $slot }}

    <div x-data="{ focused: false }">
        <span class="ml-5">
            <input @focus="focused = true" @blur="focused = false" class="sr-only" type="file" {{ $attributes }}>
            <label for="{{ $attributes['id'] }}" :class="{ 'outline-none border-blue-300 shadow-outline-blue': focused }" class="cursor-pointer py-2 px-3 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 hover:text-gray-500 rounded-md shadow-sm active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                {{ __("Change") }}
            </label>
        </span>
    </div>
</div>
