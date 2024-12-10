<div
    {{ $attributes->twMerge(['class' => 'space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0']) }}
>
    @if($title instanceof \Illuminate\View\ComponentSlot)
        {{ $title }}
    @else
        <h2
            class="font-heading text-2xl font-bold leading-6 text-gray-900 dark:text-white sm:truncate sm:text-3xl sm:leading-9"
        >
            {{ $title }}
        </h2>
    @endif

    @isset($action)
        {{ $action }}
    @endisset
</div>
