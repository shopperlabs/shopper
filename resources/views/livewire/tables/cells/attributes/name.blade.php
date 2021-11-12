<div class="flex items-center">
    <div class="flex-shrink-0 w-2 h-2 mr-1.5 rounded-full {{ $attribute->is_enabled ? 'bg-green-600': 'bg-secondary-400 dark:bg-secondary-600' }}"></div>
    <div class="flex items-center">
        <a href="{{ route('shopper.settings.attributes.edit', $attribute) }}" class="ml-2 truncate hover:text-secondary-600 dark:hover:text-secondary-300">
            <span>{{ $attribute->name }} </span>
        </a>
    </div>
</div>
