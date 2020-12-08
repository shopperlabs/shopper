@props(['initialValue' => ''])

<div
    x-data
    x-init="
        (function(easyMDE) {
            easyMDE.codemirror.on('change', function () {
                @this.set('{{ $attributes['wire:model'] }}', easyMDE.value())
            });
        } (new EasyMDE({ element: $refs.textarea })))
    "
    wire:ignore
    {{ $attributes }}
>
    <textarea name=body" id="body" x-ref="textarea" class="form-input block w-full sm:text-sm sm:leading-5">{{ $initialValue }}</textarea>
</div>
