@props(['id' => 'color'])

<div
    x-data="{
        init() {
            const pickr = Pickr.create({
                el: '.color-picker',
                theme: 'nano',

                swatches: [
                    'rgba(244, 67, 54, 1)',
                    'rgba(233, 30, 99, 1)',
                    'rgba(156, 39, 176, 1)',
                    'rgba(103, 58, 183, 1)',
                    'rgba(63, 81, 181, 1)',
                    'rgba(33, 150, 243, 1)',
                    'rgba(3, 169, 244, 1)',
                    'rgba(0, 188, 212, 1)',
                    'rgba(0, 150, 136, 1)',
                    'rgba(76, 175, 80, 1)',
                    'rgba(139, 195, 74, 1)',
                    'rgba(205, 220, 57, 1)',
                    'rgba(255, 235, 59, 1)',
                    'rgba(255, 193, 7, 1)'
                ],

                components: {
                    // Main components
                    preview: true,
                    hue: true,
                    // Input / output Options
                    interaction: {
                        hex: true,
                        input: true,
                        clear: true,
                        save: true
                    }
                }
            });
            let input = document.getElementById('{{ $id . '-input' }}');
            pickr.on('save', function (color) {
                let currentColor = color ? color.toHEXA().toString() : '';
                @this.set('key', currentColor)
                input.setAttribute('value', currentColor);
                // document.querySelector('.color-picker').setAttribute('title', currentColor);
            });
        }
    }"
    class="mt-1 relative flex items-center"
>
    <x-shopper-input.text type="text" id="{{ $id }}-input" class="pr-12" {{ $attributes }} readonly />
    <div class="absolute inset-y-0 right-0 flex items-center rounded overflow-hidden pr-1.5 pb-1">
        <span class="color-picker"></span>
    </div>
</div>

