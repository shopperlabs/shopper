@props([
    'files' => [],
    'images' => [],
    'error' => false,
])

<div x-data="dropFiles()">
    @if(count($files) > 0)
        <div class="grid grid-rows-2 grid-cols-7 gap-4">
            @foreach($files as $file)
                <div class="relative group @if($loop->first) row-span-2 col-span-3 h-44 @else col-span-1 h-20 @endif rounded-md overflow-hidden">
                    <img class="h-full w-full object-cover" src="{{ $file->temporaryUrl() }}" alt="" />
                    <div class="absolute h-full w-full flex items-center justify-center opacity-0 hover:opacity-100 focus-within:opacity-100 inset-0 bg-gray-800 bg-opacity-75 transition duration-150 ease-in-out">
                        <button wire:click="removeUpload('files', '{{ $file->getFilename() }}')" type="button" class="inline-flex p-2 border-2 border-transparent text-white rounded-full hover:text-gray-100 focus:outline-none focus:text-gray-100 focus:bg-gray-600 transition duration-150 ease-in-out">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach

            @if(count($files) <= 8)
                <div class="col-span-1">
                    <label x-bind:class="dropingFile ? 'bg-blue-50 border-blue-200' : 'border-gray-300 bg-transparent'"
                           x-on:drop="dropingFile = false"
                           x-on:drop.prevent="handleFileDrop($event)"
                           x-on:dragover.prevent="dropingFile = true"
                           x-on:dragleave.prevent="dropingFile = false" for="{{ $attributes['id'] }}" :class="{ 'border-blue-200 bg-blue-50': focused }" wire:loading.class="bg-blue-50" class="p-5 group flex justify-center border-2 border-gray-300 border-dashed hover:border-blue-200 hover:bg-blue-50 rounded-md cursor-pointer transition duration-150 ease-in-out">
                        <div class="text-center" wire:loading.remove wire:target="files">
                            <svg class="mx-auto h-8 w-8 text-gray-400 group-hover:text-blue-500" :class="{ 'text-blue-500': focused }" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <input @focus="focused = true" @blur="focused = false" @change="handleFileDrop($event)" class="sr-only" type="file" multiple {{ $attributes }} />
                        </div>
                        <div class="hidden flex items-center justify-center" wire:loading.class.remove="hidden" wire:target="files">
                            <svg wire:loading wire:target="files" class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </div>
                    </label>
                </div>
            @endif
        </div>
    @else
        <div class="flex-1 w-full">
            <label x-bind:class="dropingFile ? 'bg-blue-50 border-blue-200' : 'border-gray-300 bg-transparent'"
                   x-on:drop="dropingFile = false"
                   x-on:drop.prevent="handleFileDrop($event)"
                   x-on:dragover.prevent="dropingFile = true"
                   x-on:dragleave.prevent="dropingFile = false" for="{{ $attributes['id'] }}" :class="{ 'border-blue-200 bg-blue-50': focused }" wire:loading.class="bg-blue-50" class="px-6 py-10 group flex justify-center border-2 border-gray-300 border-dashed hover:border-blue-200 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-gray-900 rounded-md cursor-pointer transition duration-150 ease-in-out">
                <div class="space-y-1 text-center" wire:loading.remove wire:target="files">
                    <svg class="mx-auto h-8 w-8 text-gray-400 group-hover:text-blue-500" :class="{ 'text-blue-500': focused }" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-medium text-blue-600 group-hover:text-blue-500 dark:text-blue-400 dark:group-hover:text-blue-500 focus:outline-none focus:underline transition duration-150 ease-in-out">
                            {{ __('Upload a file') }}
                        </span>
                        {{ __('or drag and drop') }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ __('PNG, JPG, GIF up to 1MB') }}
                    </p>
                    <input @focus="focused = true" @blur="focused = false" @change="handleFileDrop($event)" class="sr-only" type="file" multiple {{ $attributes }} />
                </div>
                <div class="hidden flex items-center justify-center" wire:loading.class.remove="hidden" wire:target="files">
                    <x-shopper-loader wire:loading wire:target="files" class="text-blue-600" />
                </div>
            </label>
        </div>
    @endif

    @if(count($images) > 0)
        <div class="grid grid-cols-2 gap-4 mt-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
            @foreach($images as $image)
                <div class="relative flex flex-col items-center overflow-hidden h-24 text-center bg-gray-100 border rounded-md select-none dark:bg-gray-700 dark:border-gray-700">
                    <button class="absolute top-0 right-0 z-50 p-1 bg-white rounded-bl focus:outline-none dark:bg-gray-800" type="button" wire:click="removeMedia({{ $image->id }})">
                        <x-heroicon-o-trash class="w-5 h-5 text-gray-500 dark:text-gray-400" />
                    </button>
                    <img class="absolute inset-0 z-30 object-cover w-full h-full border-4 border-white dark:border-gray-800 preview" src="{{ $image->getFullUrl() }}" alt="">
                    <div class="absolute bottom-0 left-0 right-0 flex flex-col p-2 text-xs bg-white dark:bg-gray-800 bg-opacity-50 z-40 dark:bg-opacity-70">
                        <span class="w-full font-bold text-gray-900 truncate dark:text-white">{{ $image->file_name }}</span>
                        <span class="text-xs text-gray-900 dark:text-white">{{ $image->human_readable_size }}</span>
                    </div>
                    <div wire:loading.class.remove="hidden" wire:target="removeMedia({{ $image->id }})" class="hidden absolute h-full w-full flex items-center justify-center text-center opacity-100 focus-within:opacity-100 inset-0 z-50 bg-gray-800 bg-opacity-75">
                        <x-shopper-loader wire:loading wire:target="removeMedia({{ $image->id }})" class="text-white" />
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($error)
        <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>

@push('scripts')
    <script>
        function dropFiles() {
            return {
                focused: false,
                dropingFile: false,
                handleFileDrop(e) {
                    if (event.dataTransfer.files.length > 0) {
                        const files = e.dataTransfer.files;
                        @this.uploadMultiple('files', files, (uploadedFilename) => {}, () => {}, (event) => {});
                    }
                }
            };
        }
    </script>
@endpush
