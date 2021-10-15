@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.css" />
@endpush

<div
    wire:ignore
    x-data
    @trix-change="$dispatch('change', @this.set('value', $event.target.value))"
>
    <input id="{{ $trixId }}" value="{{ $value }}" type="hidden" class="sr-only">
    <trix-editor input="{{ $trixId }}" class="block w-full rounded-md shadow-sm border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-700 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm max-h-96 overflow-y-scroll"></trix-editor>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.3.1/trix.min.js"></script>
@endpush
