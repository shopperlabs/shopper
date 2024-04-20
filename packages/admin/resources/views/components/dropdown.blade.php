@props([
    'align' => 'right',
    'width' => '48',
    'customAlignmentClasses' => '',
    'contentClasses' => 'bg-white dark:bg-gray-700',
    'containerClasses' => 'relative block text-left',
])

@php
    switch ($align) {
        case 'left':
            $alignmentClasses = 'origin-top-left ' . $customAlignmentClasses;
            break;
        case 'top':
            $alignmentClasses = 'origin-top ' . $customAlignmentClasses;
            break;
        case 'right':
        default:
            $alignmentClasses = 'origin-top-right ' . $customAlignmentClasses;
            break;
    }

    switch ($width) {
        case '48':
            $width = 'w-48';
            break;
        case '56':
            $width = 'w-56';
            break;
    }
@endphp

<div
    x-data="{ open: false }"
    @click.away="open = false"
    @close.stop="open = false"
    x-on:notify.window="open = false"
    class="{{ $containerClasses }}"
>
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="scale-95 transform opacity-0"
        x-transition:enter-end="scale-100 transform opacity-100"
        x-transition:leave="transition duration-75 ease-in"
        x-transition:leave-start="scale-100 transform opacity-100"
        x-transition:leave-end="scale-95 transform opacity-0"
        class="{{ $width }} {{ $alignmentClasses }} absolute z-50 mt-2 rounded-lg shadow-lg"
        style="display: none"
        role="menu"
        @click="open = false"
    >
        <div class="shadow-xs {{ $contentClasses }} rounded-md">
            {{ $content }}
        </div>
    </div>
</div>
