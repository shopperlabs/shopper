<div {{ $attributes->twMerge(['class' => 'space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0']) }}>
    <h2 class="text-2xl font-heading font-bold leading-6 text-gray-900 dark:text-white sm:text-3xl sm:leading-9 sm:truncate">
        {{ $title }}
    </h2>
    @isset($action)
        {{ $action }}
    @endisset
</div>
