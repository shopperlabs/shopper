<div class="flex justify-center text-center my-10">
    <div class="bg-gray-50 overflow-hidden shadow-md rounded-md flex items-center">
        <div class="p-3 flex items-center justify-center flex-shrink-0 border-r border-gray-200">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="px-4 py-3 bg-white text-gray-500 text-sm leading-5">
            {{ __("Learn more about") }}
            <a href="{{ $link }}" target="_blank" class="ml-1 inline-flex items-center text-blue-600 hover:text-blue-500 transition-colors duration-150 ease-in-out">
                {{ __(ucfirst($name)) }}
                <span class="ml-1.5">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </span>
            </a>
        </div>
    </div>
</div>
