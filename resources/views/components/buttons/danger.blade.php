<button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-md border border-transparent px-4 py-2 bg-danger-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-danger-500 focus:outline-none focus:border-danger-700 focus:shadow-outline-danger transition ease-in-out duration-150 sm:text-sm sm:leading-5']) }}>
    {{ $slot }}
</button>
