<div class="pb-5 border-b border-gray-200 dark:border-gray-700 space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0">
    <h2 class="text-2xl font-bold leading-6 text-gray-900 dark:text-white sm:text-3xl sm:leading-9 sm:truncate">{{ $title }}</h2>
    @if(isset($action))
        {{ $action }}
    @endif
</div>
