@props([
    'file' => false,
    'error' => false,
])

<div x-data="{ focused: false }">
    @if($file)
        <div class="flex-shrink-0 rounded-md overflow-hidden">
            <img class="h-40 w-full object-cover rounded-md" src="{{ $file->temporaryUrl() }}" alt="" />
            <div class="flex items-center mt-2">
                <button {{ $attributes->wire('click') }} type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                    <svg class="h-5 w-5 mr-1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    {{ __("Remove") }}
                </button>
            </div>
        </div>
    @else
        <div class="w-full">
            <label for="{{ $attributes['id'] }}" :class="{ 'border-blue-200 bg-blue-50': focused }" class="group flex justify-center border-2 border-gray-300 border-dashed hover:border-blue-200 hover:bg-blue-50 rounded-md cursor-pointer transition duration-150 ease-in-out">
                <div class="px-6 pt-5 pb-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-blue-500" :class="{ 'text-blue-500': focused }" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <p class="mt-1 text-sm text-gray-600 group-hover:text-blue-500">
                        <span :class="{ 'text-blue-800': focused }" class="font-medium text-blue-600 group-hover:text-blue-800 focus:outline-none focus:underline transition duration-150 ease-in-out">
                            {{ __("Upload a file") }}
                        </span>
                        {{ __("or drag and drop") }}
                    </p>
                    <p :class="{ 'text-blue-500': focused }" class="mt-1 text-xs text-gray-500 group-hover:text-blue-500">
                        {{ __('PNG, JPG, GIF up to 1MB') }}
                    </p>
                    <input @focus="focused = true" @blur="focused = false" class="sr-only" type="file" {{ $attributes }} />
                </div>
            </label>
        </div>
    @endif
    @if($error)
        <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>
