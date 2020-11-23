<span class="rounded-md shadow-sm">
    <button {{ $attributes->merge(['class' => 'inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150']) }}>
        {{ $slot }}
    </button>
</span>
