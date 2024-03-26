<div {{ $attributes->twMerge(['class' => 'pb-5 border-b border-gray-200 dark:border-gray-700 space-y-3 md:flex md:items-center md:justify-between md:space-x-4 md:space-y-0']) }}>
    <h2 class="text-2xl font-heading font-bold leading-6 text-gray-900 dark:text-white sm:text-3xl sm:leading-9 sm:truncate">
        {{ $title }}
    </h2>
    @isset($action)
        {{ $action }}
    @endisset
</div>
