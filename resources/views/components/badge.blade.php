@props(['style', 'value'])

@php
    switch ($style ?? 'secondary') {
        case 'primary':
          $style = 'bg-primary-100 text-primary-800';
          break;
        case 'orange':
          $style = 'bg-orange-100 text-orange-800';
          break;
        case 'danger':
          $style = 'bg-danger-100 text-danger-800';
          break;
        case 'success':
          $style = 'bg-green-100 text-green-800';
          break;
        case 'secondary':
        default:
          $style = 'bg-secondary-100 text-secondary-800';
          break;
    }
@endphp

<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $style }}">
    {{ $value }}
</span>
