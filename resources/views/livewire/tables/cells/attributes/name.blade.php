<div class="flex items-center">
    <div class="shrink-0 w-2 h-2 mr-1.5 rounded-full {{ $row->is_enabled ? 'bg-green-600': 'bg-secondary-400 dark:bg-secondary-600' }}"></div>
    <div class="flex items-center ml-2 truncate text-secondary-900 dark:text-white">
        <span>{{ $row->name }}</span>
    </div>
</div>
