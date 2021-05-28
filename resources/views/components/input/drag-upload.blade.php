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
                    <x-heroicon-o-trash class="h-5 w-5 mr-1.5" />
                    {{ __('Remove') }}
                </button>
            </div>
        </div>
    @else
        <div class="w-full">
            <label for="{{ $attributes['id'] }}" :class="{ 'border-blue-200 bg-blue-50': focused }" class="group flex justify-center border-2 border-gray-300 dark:border-gray-700 border-dashed hover:border-blue-200 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-gray-900 rounded-md cursor-pointer">
                <div class="px-6 pt-5 pb-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-blue-500" :class="{ 'text-blue-500': focused }" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 group-hover:text-blue-500">
                        <span :class="{ 'text-blue-700': focused }" class="font-medium text-blue-600 group-hover:text-blue-700 focus:underline">
                            {{ __('Upload a file') }}
                        </span>
                        {{ __('or drag and drop') }}
                    </p>
                    <p :class="{ 'text-blue-500': focused }" class="mt-1 text-xs text-gray-500 dark:text-gray-400 group-hover:text-blue-500">
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
