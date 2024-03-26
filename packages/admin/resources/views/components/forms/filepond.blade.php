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
                maxFileSize: {!! $attributes->has('maxFileSize') ? "'" . $attributes->get('maxFileSize') . "'" : 'null' !!},
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
                <div class="relative flex flex-col items-center overflow-hidden h-24 text-center bg-gray-100 border rounded-md select-none dark:bg-gray-700 dark:border-gray-700">
                    <button class="absolute top-0 right-0 z-50 p-1 bg-white rounded-bl focus:outline-none dark:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300" type="button" wire:click="removeMedia({{ $image->id }})">
                        <x-untitledui-trash-03 class="w-5 h-5" />
                    </button>
                    <img class="absolute inset-0 z-30 object-cover w-full h-full border-4 border-white dark:border-gray-800 preview" src="{{ $image->getFullUrl() }}" alt="">
                    <div wire:loading.class.remove="hidden" wire:target="removeMedia({{ $image->id }})" class="hidden absolute h-full w-full items-center justify-center text-center opacity-100 focus-within:opacity-100 inset-0 z-50 bg-gray-800 bg-opacity-75">
                        <x-shopper::loader wire:loading wire:target="removeMedia({{ $image->id }})" class="text-white" />
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
