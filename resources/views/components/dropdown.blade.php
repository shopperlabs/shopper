@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'bg-white', 'containerClasses' => 'relative block text-left'])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'origin-top-left left-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top';
        break;
    case 'right':
    default:
        $alignmentClasses = 'origin-top-right right-0';
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

<div x-data="{ open: false }" @click.away="open = false" @close.stop="open = false" x-on:notify.window="open = false" class="{{ $containerClasses }}">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-50 mt-2 {{ $width }} rounded-md shadow-lg {{ $alignmentClasses }}"
         style="display: none;"
         role="menu"
         @click="open = false">
        <div class="rounded-md shadow-xs {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
