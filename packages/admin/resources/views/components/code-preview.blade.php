@props([
    'code',
    'lang',
    'themes',
])

<div
    {{ $attributes->twMerge(['class' => 'mt-2 text-sm rounded-lg overflow-hidden']) }}
    x-data="codePreview({
                code: @js(json_encode($code)),
                lang: @js($lang),
                themes: @js($themes),
            })"
></div>
