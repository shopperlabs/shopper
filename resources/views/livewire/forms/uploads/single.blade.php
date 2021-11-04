<div x-data="{ focused: false }">
    @if($file)
        <div class="flex-shrink-0 rounded-md overflow-hidden">
            <img class="h-40 w-full object-cover rounded-md" src="{{ $file->temporaryUrl() }}" alt="" />
            <div class="flex items-center mt-2">
                <button wire:click="removeSingleMediaPlaceholder" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                    <x-heroicon-o-trash class="h-5 w-5 mr-1.5" />
                    {{ __('Remove') }}
                </button>
            </div>
        </div>
    @else
        <label for="{{ $inputId }}" :class="{ 'border-primary-200 bg-primary-50 dark:bg-gray-800': focused }" class="mt-2 group flex justify-center border-2 border-gray-300 dark:border-gray-600 border-dashed hover:border-primary-200 dark:hover:border-gray-600 hover:bg-primary-50 dark:hover:bg-gray-700 rounded-md cursor-pointer overflow-hidden">
            <div class="w-full px-6 pt-5 pb-6 text-center" wire:loading.remove wire:target="file">
                <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-gray-500" :class="{ 'text-gray-500': focused }" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p class="mt-1 text-sm text-gray-500 group-hover:text-gray-400 dark:text-gray-400 dark:group-hover:text-gray-300">
                    <span :class="{ 'text-primary-500': focused }" class="font-medium text-primary-600 group-hover:text-primary-500 dark:text-primary-400 dark:group-hover:text-primary-500 focus:outline-none focus:underline transition duration-150 ease-in-out">
                        {{ __('Click to upload a file') }}
                    </span>
                </p>
                <p :class="{ 'text-gray-400': focused }" class="mt-1 text-xs text-gray-500 group-hover:text-gray-400 dark:text-gray-400 dark:group-hover:text-gray-300">
                    {{ __('PNG, JPG, GIF up to 5MB') }}
                </p>
                <input @focus="focused = true" @blur="focused = false" class="sr-only" type="file" wire:model="file" id="{{ $inputId }}" />
            </div>
            <div class="w-full h-32 bg-gray-50 dark:bg-gray-700 p-6 hidden flex items-center justify-center" wire:loading.class.remove="hidden" wire:target="file">
                <x-shopper-loader wire:loading wire:target="file" class="text-primary-600" />
            </div>
        </label>
    @endif
    @if($media)
        <div class="flex items-center justify-between mt-4 p-2 bg-gray-50 rounded-md border border-dashed border-gray-200 dark:bg-gray-700 dark:border-gray-600">
            <div class="flex flex-1 items-center space-x-2 truncate">
                <div class="flex-shrink-0 w-10 h-10 overflow-hidden rounded-md shadow-md">
                    <img class="h-full w-full object-cover" src="{{ $media->getFullUrl() }}" alt="{{ $media->file_name }}">
                </div>
                <div class="truncate">
                    <h4 class="text-sm leading-5 text-gray-500 truncate dark:text-gray-400">{{ $media->file_name }}</h4>
                    <p class="text-xs leading-4 text-gray-400 dark:text-gray-500">{{ $media->human_readable_size }}</p>
                </div>
            </div>
            <button wire:click="removeMedia({{ $media->id }})" wire:loading.attr="disabled" type="button" class="ml-4 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                <x-shopper-loader wire:loading wire:target="removeMedia" class="text-white" />
                <x-heroicon-o-trash wire:loading.remove class="h-5 w-5" />
            </button>
        </div>
    @endif

    @error('file')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
