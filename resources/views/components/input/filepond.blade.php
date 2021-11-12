@props(['images' => []])

<div>
    <div
        wire:ignore
        x-data
        x-init="
          $nextTick(() => {
            const post = FilePond.create($refs.input);
            post.setOptions({
                allowMultiple: {{ $attributes->has('multiple') ? 'true' : 'false' }},
                allowImagePreview: {{ $attributes->has('allowFileTypeValidation') ? 'true' : 'false' }},
                imagePreviewMaxHeight: {{ $attributes->has('imagePreviewMaxHeight') ? $attributes->get('imagePreviewMaxHeight') : '256' }},
                allowFileTypeValidation: {{ $attributes->has('allowFileTypeValidation') ? 'true' : 'false' }},
                acceptedFileTypes: {!! $attributes->get('acceptedFileTypes') ?? 'null' !!},
                allowFileSizeValidation: {{ $attributes->has('allowFileSizeValidation') ? 'true' : 'false' }},
                maxFileSize: {!! $attributes->has('maxFileSize') ? "'".$attributes->get('maxFileSize')."'" : 'null' !!},
                server: {
                    process:(fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        @this.upload('{{ $attributes->whereStartsWith('wire:model')->first() }}', file, load, error, progress)
                    },
                    revert: (filename, load) => {
                        @this.removeUpload('{{ $attributes->whereStartsWith('wire:model')->first() }}', filename, load)
                    },
                },
            });
          });
       "
    >
        <input type="file" x-ref="{{ $attributes->get('ref') ?? 'input' }}" />
    </div>

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
</div>

@push('styles')
    @once
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
    @endonce
@endpush

@push('scripts')
    @once
        <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
        <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
        <script>
            FilePond.registerPlugin(FilePondPluginFileValidateType);
            FilePond.registerPlugin(FilePondPluginFileValidateSize);
            FilePond.registerPlugin(FilePondPluginImagePreview);
        </script>
    @endonce
@endpush
