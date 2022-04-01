@props(['files' => [], 'images' => []])

<div x-data="uploadsMultiple" class="relative">
    @if(collect($files)->isNotEmpty())
        <ul class="grid grid-rows-2 grid-cols-7 gap-4">
            @foreach($files as $file)
                <li class="relative group @if($loop->first) row-span-2 col-span-3 h-44 @else col-span-1 h-20 @endif border border-secondary-300 dark:border-secondary-700 rounded-md overflow-hidden">
                    <img class="h-full w-full object-cover" src="{{ $file->temporaryUrl() }}" alt="" />
                    <div class="absolute h-full w-full flex items-center justify-center opacity-0 hover:opacity-100 focus-within:opacity-100 inset-0 bg-secondary-800 bg-opacity-75 transition duration-150 ease-in-out">
                        <button wire:click="removeUpload('files', '{{ $file->getFilename() }}')" type="button" class="inline-flex p-2 border-2 border-transparent text-white rounded-full hover:text-secondary-100 focus:outline-none focus:text-secondary-100 focus:bg-secondary-600 transition duration-150 ease-in-out">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </li>
            @endforeach

            @if(count($files) <= 8)
                <li class="col-span-1">
                    <div x-ref="dragContainer"
                         :class="focused ? 'bg-primary-50 border-primary-200 ' : 'border-secondary-300 dark:border-secondary-700 bg-transparent'"
                         class="relative group flex justify-center p-5 border-2 border-secondary-300 border-dashed hover:border-primary-200 hover:bg-primary-50 rounded-md cursor-pointer transition duration-150 ease-in-out"
                    >
                        <div class="text-center">
                            <svg class="mx-auto h-8 w-8 text-secondary-400 group-hover:text-primary-500" :class="{ 'text-primary-500': focused }" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <input
                            accept="image/*"
                            class="absolute inset-0 z-50 w-full h-full outline-none opacity-0 cursor-pointer"
                            type="file"
                            multiple
                            @focus="focused = true"
                            @blur="focused = false"
                            @change="addFiles($event)"
                            @dragover="$refs.dragContainer.classList.add('border-primary-200'); $refs.dragContainer.classList.add('ring-2'); $refs.dragContainer.classList.add('ring-inset');"
                            @dragleave="$refs.dragContainer.classList.remove('border-primary-200'); $refs.dragContainer.classList.remove('ring-2'); $refs.dragContainer.classList.remove('ring-inset');"
                            @drop="$refs.dragContainer.classList.remove('border-primary-200'); $refs.dragContainer.classList.remove('ring-2'); $refs.dragContainer.classList.remove('ring-inset');"
                            {{ $attributes }}
                        />
                    </div>
                </li>
            @endif
        </ul>
    @else
        <div x-ref="dragContainer"
             :class="focused ? 'bg-primary-50 border-primary-200 ' : 'border-secondary-300 dark:border-secondary-700 bg-transparent'"
             class="relative flex-1 w-full px-6 py-10 group flex justify-center border-2 border-secondary-300 border-dashed hover:border-primary-200 dark:border-secondary-700 hover:bg-primary-50 dark:hover:bg-secondary-900 rounded-md cursor-pointer transition duration-150 ease-in-out"
        >
            <div class="space-y-1 text-center" wire:loading.remove wire:target="files">
                <svg class="mx-auto h-8 w-8 text-secondary-400 group-hover:text-primary-500" :class="{ 'text-primary-500': focused }" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-400">
                    <span class="font-medium text-primary-600 group-hover:text-primary-500 dark:group-hover:text-primary-500 focus:outline-none focus:underline transition duration-150 ease-in-out">
                        {{ __('Upload a file') }}
                    </span>
                    {{ __('or drag and drop') }}
                </p>
                <p class="mt-1 text-xs text-secondary-500 dark:text-secondary-400">
                    {{ __('PNG, JPG, GIF up to 1MB') }}
                </p>
            </div>
            <input
                accept="image/*"
                class="absolute inset-0 z-50 w-full h-full outline-none opacity-0 cursor-pointer"
                type="file"
                multiple
                @focus="focused = true"
                @blur="focused = false"
                @change="addFiles($event)"
                @dragover="$refs.dragContainer.classList.add('border-primary-200'); $refs.dragContainer.classList.add('ring-2'); $refs.dragContainer.classList.add('ring-inset');"
                @dragleave="$refs.dragContainer.classList.remove('border-primary-200'); $refs.dragContainer.classList.remove('ring-2'); $refs.dragContainer.classList.remove('ring-inset');"
                @drop="$refs.dragContainer.classList.remove('border-primary-200'); $refs.dragContainer.classList.remove('ring-2'); $refs.dragContainer.classList.remove('ring-inset');"
                {{ $attributes }}
            />
        </div>
    @endif

    @if(collect($images)->isNotEmpty())
        <div class="grid grid-cols-2 gap-4 mt-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
            @foreach($images as $image)
                <div class="relative flex flex-col items-center overflow-hidden h-24 text-center bg-secondary-100 border rounded-md select-none dark:bg-secondary-700 dark:border-secondary-700">
                    <button class="absolute top-0 right-0 z-50 p-1 bg-white rounded-bl focus:outline-none dark:bg-secondary-800" type="button" wire:click="removeMedia({{ $image->id }})">
                        <x-heroicon-o-trash class="w-5 h-5 text-secondary-500 dark:text-secondary-400" />
                    </button>
                    <img class="absolute inset-0 z-30 object-cover w-full h-full border-4 border-white dark:border-secondary-800 preview" src="{{ $image->getFullUrl() }}" alt="">
                    <div class="absolute bottom-0 left-0 right-0 flex flex-col p-2 text-xs bg-white dark:bg-secondary-800 bg-opacity-50 z-40 dark:bg-opacity-70">
                        <span class="w-full font-bold text-secondary-900 truncate dark:text-white">{{ $image->file_name }}</span>
                        <span class="text-xs text-secondary-900 dark:text-white">{{ $image->human_readable_size }}</span>
                    </div>
                    <div wire:loading.class.remove="hidden" wire:target="removeMedia({{ $image->id }})" class="hidden absolute h-full w-full flex items-center justify-center text-center opacity-100 focus-within:opacity-100 inset-0 z-50 bg-secondary-800 bg-opacity-75">
                        <x-shopper-loader wire:loading wire:target="removeMedia({{ $image->id }})" class="text-white" />
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="hidden mt-2 flex items-center" wire:loading.class.remove="hidden" wire:target="files">
        <x-shopper-loader wire:loading wire:target="files" class="text-primary-600" />
        <span class="ml-1.5 text-sm text-secondary-500 dark:text-secondary-400">{{ __('Uploading...') }}</span>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('uploadsMultiple', () => ({
                files: @entangle('files'),
                focused: false,

                addFiles(e) {
                    @this.uploadMultiple('files', e.target.files, (uploadedFilename) => {}, () => {}, (event) => {});
                }
            }))
        })
    </script>
@endpush
