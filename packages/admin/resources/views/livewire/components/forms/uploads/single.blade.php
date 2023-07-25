<div x-data="{ focused: false }">
    @if($file)
        <div class="shrink-0 rounded-md overflow-hidden">
            <img class="h-40 w-full object-cover rounded-md" src="{{ $file->temporaryUrl() }}" alt="" />
            <div class="flex items-center mt-2">
                <button wire:click="removeSingleMediaPlaceholder" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                    <x-untitledui-trash-03 class="h-5 w-5 mr-1.5" />
                    {{ __('shopper::layout.forms.actions.remove') }}
                </button>
            </div>
        </div>
    @else
        <label for="{{ $inputId }}" :class="{ 'border-primary-200 bg-primary-50 dark:bg-secondary-800': focused }" class="mt-2 group flex justify-center border-2 border-secondary-300 dark:border-secondary-600 border-dashed hover:border-primary-200 dark:hover:border-secondary-600 hover:bg-primary-50 dark:hover:bg-secondary-700 rounded-md cursor-pointer overflow-hidden">
            <div class="w-full px-6 pt-5 pb-6 text-center" wire:loading.remove wire:target="file">
                <x-untitledui-image-plus class="mx-auto h-12 w-12 text-secondary-400 group-hover:text-secondary-500 dark:text-secondary-500 dark:group-hover:text-gray-400" stroke-width="1" />
                <p class="mt-1 text-sm text-secondary-500 group-hover:text-secondary-400 dark:text-secondary-400 dark:group-hover:text-secondary-300">
                    <span :class="{ 'text-primary-500': focused }" class="font-medium text-primary-600 group-hover:text-primary-500 focus:outline-none focus:underline transition duration-150 ease-in-out">
                        {{ __('shopper::components.files.file') }}
                    </span>
                </p>
                <p :class="{ 'text-secondary-400': focused }" class="mt-1 text-xs text-secondary-500 group-hover:text-secondary-400 dark:text-secondary-400 dark:group-hover:text-secondary-300">
                    {{ __('shopper::components.files.type_size', ['size' => 5]) }}
                </p>
                <input @focus="focused = true" @blur="focused = false" class="sr-only" type="file" wire:model="file" id="{{ $inputId }}" />
            </div>
            <div class="w-full h-32 bg-secondary-50 dark:bg-secondary-700 p-6 hidden flex-1 items-center justify-center" wire:loading.class.remove="hidden" wire:loading.class="flex" wire:target="file">
                <x-shopper::loader wire:loading wire:target="file" class="text-primary-600" />
            </div>
        </label>
    @endif
    @if($media)
        <div class="flex items-center justify-between mt-4 p-2 bg-secondary-50 rounded-md border border-dashed border-secondary-200 dark:bg-secondary-700 dark:border-secondary-600">
            <div class="flex flex-1 items-center space-x-2 truncate">
                <div class="shrink-0 w-10 h-10 overflow-hidden rounded-md shadow-md">
                    <img class="h-full w-full object-cover" src="{{ $media->getFullUrl() }}" alt="{{ $media->file_name }}">
                </div>
                <div class="truncate">
                    <h4 class="text-sm leading-5 text-secondary-500 truncate dark:text-secondary-400">{{ $media->file_name }}</h4>
                    <p class="text-xs leading-4 text-secondary-400 dark:text-secondary-500">{{ $media->human_readable_size }}</p>
                </div>
            </div>
            <button wire:click="removeMedia({{ $media->id }})" wire:loading.attr="disabled" type="button" class="ml-4 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                <x-shopper::loader wire:loading wire:target="removeMedia" class="text-white" />
                <x-untitledui-trash-03 wire:loading.remove class="h-5 w-5" />
            </button>
        </div>
    @endif

    @error('file')
        <p class="mt-2 text-sm text-danger-600">{{ $message }}</p>
    @enderror
</div>
