<div class="flex items-center text-secondary-500 dark:text-secondary-400">
    <div class="shrink-0 h-8 w-8">
        <img class="h-8 w-8 rounded-full" src="{{ $row->author->picture }}" alt="">
    </div>
    <div class="ml-4 truncate">
        <div class="text-sm leading-5 font-medium text-secondary-900 dark:text-white">{{ $row->author->full_name }}</div>
        <div class="text-xs leading-4 text-secondary-500 truncate dark:text-secondary-400">{{ $row->author->email }}</div>
    </div>
</div>
